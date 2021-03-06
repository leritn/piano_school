<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TimeHelper;
use DB;
use DateTime;
use DateInterval;

class Schedule extends Model
{
	protected $table = 'students_teachers';

	public static $rules = array(
		'teachers_id' => 'required' ,
		'students_id' => 'required' ,
		'class_date' => 'required' ,
		'start_time' => 'required' ,
		'end_time' => 'required' ,
		'location' => 'required',
		

		
		);

	public static $rules_update = array(
		'teacher_id' => 'required' ,
		'student_id' => 'required' ,
		'class_date_display' => 'required' ,
		'start_time_display' => 'required' ,
		'end_time_display' => 'required' ,
		'location' => 'required',
		);

	public static function scheduleList ($date = NULL, $query = NULL)
	{
		$schedules = DB::table('students_teachers')
		->join('users as students', 'students.students_id', '=', 'students_teachers.students_id')
		->join('users as teachers', 'teachers.teachers_id', '=', 'students_teachers.teachers_id')
		->select('students_teachers.id as id', 
			'students_teachers.start_time as start_time', 
			'students_teachers.end_time as end_time', 
			'students_teachers.teachers_id as teachers_id', 
			'students_teachers.students_id as students_id', 
			'students.nickname as student_nickname', 
			'students.firstname as student_firstname', 
			'students.lastname as student_lastname',
			'teachers.nickname as teacher_nickname', 
			'teachers.firstname as teacher_firstname', 
			'teachers.lastname as teacher_lastname',
			'students_teachers.status as status')
		->orderBy('students_teachers.start_time', 'asc');

		if(!is_null($date))
		{
			$schedules = $schedules->where('start_time', '>', $date . " 00:00:00")
			->where('end_time', '<', $date . " 23:59:59");
		}

		if(!is_null($query))
		{
			$query = trim($query);

			$schedules = $schedules->where(function( $filter ) use ( $query ) {
				$filter->where('students.nickname', 'LIKE', "%$query%")
				->orWhere('students.firstname', 'LIKE', "%$query%")
				->orWhere('students.lastname', 'LIKE', "%$query%")
				->orWhere('teachers.nickname', 'LIKE', "%$query%")
				->orWhere('teachers.firstname', 'LIKE', "%$query%")
				->orWhere('teachers.lastname', 'LIKE', "%$query%");
			});
		}

		return $schedules;
	}
	public static function scheduleById ($id){
		$scheduleById = DB::table('students_teachers')
		->join('users as students', 'students.students_id', '=', 'students_teachers.students_id')
		->join('users as teachers', 'teachers.teachers_id', '=', 'students_teachers.teachers_id')
		->select('students_teachers.id as id', 
			'students_teachers.start_time as start_time', 
			'students_teachers.end_time as end_time', 
			'students_teachers.teachers_id as teachers_id', 
			'students_teachers.students_id as students_id', 
			'students.nickname as student_nickname', 
			'students.firstname as student_firstname', 
			'students.lastname as student_lastname',
			'teachers.nickname as teacher_nickname', 
			'teachers.firstname as teacher_firstname', 
			'teachers.lastname as teacher_lastname', 
			'students_teachers.location as location',
			'students_teachers.status as status')
		->where('students_teachers.id' , '=' , $id )
		->first();

		return $scheduleById;
	}

	public static function _scheduleOfTeacher_Student($teachers_id = null, $students_id = null, $date, $mode = null)
	{

		$schedules = DB::table('students_teachers')
		->join('users as students', 'students.students_id', '=', 'students_teachers.students_id')
		->join('users as teachers', 'teachers.teachers_id', '=', 'students_teachers.teachers_id')
		->select('students_teachers.id as id', 
			'students_teachers.start_time as start_time', 
			'students_teachers.end_time as end_time', 
			'students_teachers.teachers_id as teachers_id', 
			'students_teachers.students_id as students_id', 
			'students.nickname as student_nickname', 
			'students.firstname as student_firstname', 
			'students.lastname as student_lastname', 
			'teachers.nickname as teacher_nickname', 
			'teachers.firstname as teacher_firstname', 
			'teachers.lastname as teacher_lastname', 
			'students_teachers.location as location',
			'students_teachers.status as status')
		->orderBy('students_teachers.start_time', 'asc');

		
		$schedules = $schedules->where('start_time', '>=', $date);

		

		return $schedules;
	}

	public static function setStatus($request)
	{
		$schedule  = Schedule::where('students_teachers.id',$request->id)->first();

		if($request->req == 'cancel' && $schedule->status == 'Reserved'){
			$schedule->status = 'Canceled';
		}
		if($request->req == 'confirm' && $schedule->status == 'Reserved')
		{ 
			$schedule->status = 'Finished';
		}
		elseif($request->req == 'confirm' && $schedule->status == 'Finished')
		{
			$schedule->status = 'Reserved';
		}
		$schedule->save();

		return $schedule->status;

	}

	public function time(){
		return TimeHelper::calculateElapsedTime($this->start_time , $this->end_time);
	}

	public static function getTimeStudied($students_id, $teachers_id)
	{
		$schedules = Schedule::where('students_id',$students_id)
		->where('teachers_id', $teachers_id)
		->where('status' , 1)
		->get();

		$totalTimeStudied = 0;

		foreach ($schedules as $schedule) {
			$totalTimeStudied += $schedule->time();
		}

		return $totalTimeStudied;
	}

	public static function getReservedClassTime($students_id, $teachers_id)
	{
		$schedules = Schedule::where('students_id',$students_id)
		->where('teachers_id', $teachers_id)
		->where('status' , 2)
		->get();

		$totalReservedClassTime = 0;

		foreach ($schedules as $schedule) {
			$totalReservedClassTime += $schedule->time();
		}

		return $totalReservedClassTime;
	}

	public static function getTeacherSchedule($teacher_id, $start_date_timestamp, $end_date_timestamp)
	{

		$schedules = DB::table('students_teachers')
		->join('users as students', 'students.students_id', '=', 'students_teachers.students_id')
		->join('users as teachers', 'teachers.teachers_id', '=', 'students_teachers.teachers_id')
		->select('students_teachers.id as id', 
			'students_teachers.start_time as start_time', 
			'students_teachers.end_time as end_time', 
			'students_teachers.teachers_id as teachers_id', 
			'students_teachers.students_id as students_id', 
			'students.nickname as student_nickname', 
			'students.firstname as student_firstname', 
			'students.lastname as student_lastname', 
			'teachers.nickname as teacher_nickname', 
			'teachers.firstname as teacher_firstname', 
			'teachers.lastname as teacher_lastname', 
			'students_teachers.location as location',
			'students_teachers.status as status')
		->where('teachers.teachers_id','=',$teacher_id)
		->where('start_time','>=',$start_date_timestamp)
		->where('end_time','<=',$end_date_timestamp)
		->orderBy('students_teachers.start_time', 'asc')
		->get();



		$schedules_table = array();
		

		foreach ($schedules as $schedule) {
			


			$start = new DateTime($schedule->start_time);
			$end = new DateTime($schedule->end_time);
			$hour_count = $end->diff($start)->format('%h'); 
			$key = $start->format('D d M');

			for($i=1; $i<=$hour_count; $i++) {
				$time_key = $start->format('H:00');
				$start->modify('+1 hour'); // Add 1 Hour
				//$time_key .= '-'.$start->format('H.00');
				$schedules_table[$key][$time_key] = $schedule->student_nickname;
				
			}
		}

		return $schedules_table;
	}
	public static function getScheduleId($teacher_id, $start_date_timestamp, $end_date_timestamp){
		$schedules = DB::table('students_teachers')
		->join('users as students', 'students.students_id', '=', 'students_teachers.students_id')
		->join('users as teachers', 'teachers.teachers_id', '=', 'students_teachers.teachers_id')
		->select('students_teachers.id as id' ,
			'students_teachers.start_time as start_time', 
			'students_teachers.end_time as end_time', 
			'students_teachers.teachers_id as teachers_id', 
			'students_teachers.students_id as students_id', 
			'students.nickname as student_nickname', 
			'students.firstname as student_firstname', 
			'students.lastname as student_lastname', 
			'teachers.nickname as teacher_nickname', 
			'teachers.firstname as teacher_firstname', 
			'teachers.lastname as teacher_lastname', 
			'students_teachers.location as location',
			'students_teachers.status as status')	
			
		->where('teachers.teachers_id','=',$teacher_id)
		->where('start_time','>=',$start_date_timestamp)
		->where('end_time','<=',$end_date_timestamp)
		->orderBy('students_teachers.start_time', 'asc')
		->get();

		$schedule_is_array = array();

		foreach ($schedules as $schedule) {
			$start = new DateTime($schedule->start_time);
			$end = new DateTime($schedule->end_time);
			$hour_count = $end->diff($start)->format('%h'); 
			$key = $start->format('D d M');

			for($i=1; $i<=$hour_count; $i++) {
				$time_key = $start->format('H:00');
				$start->modify('+1 hour'); // Add 1 Hour
				//$time_key .= '-'.$start->format('H.00');
				$schedule_is_array[$key][$time_key] = $schedule->id;
				
			}
		}
		return$schedule_is_array;
	}

	public static function checkDateTimeSchedule($teacher_id, $start_date_timestamp, $end_date_timestamp) {

		$parameter = array(
			'teachers_id' => $teacher_id,
			'start_date_timestamp1' => $start_date_timestamp ,
			'start_date_timestamp2' => $start_date_timestamp ,
			'end_date_timestamp1' => $end_date_timestamp ,
			'end_date_timestamp2' => $end_date_timestamp 
			
			);
		$schedule = DB::select (DB::raw('
			SELECT * FROM `students_teachers` WHERE 
			`teachers_id` = :teachers_id and 
			(
				(`start_time` >= :start_date_timestamp1 and `start_time` <  :end_date_timestamp1) 
				or 
				(`end_time` >= :start_date_timestamp2 and  `end_time` < :end_date_timestamp2)) '
		), $parameter);
	return $schedule;
	}


	public static function getTeacherScheduleFromSelectedDate ($start_date,$day_count) {
		
     $date_range_selected[$start_date->format('Y-m-d')] = $start_date->format('D d M');
        for ($i=0; $i < $day_count ; $i++) { 
            $start_date->add(new DateInterval('P1D'));
            $date_range_selected[$start_date->format('Y-m-d')] = $start_date->format('D d M');   
        }

        return $date_range_selected;
    }
	
}