<?php namespace App\models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\models\Schedule;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class Teacher extends Model
{
    public $timestamps = false;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function students()
    {
        return $this->belongsToMany('App\models\Student', 'students_teachers', 'teachers_id', 'students_id')->groupBy('teachers_id');
    }

    public static $rules = array(
            'firstname' => 'required' ,
            'lastname' => 'required' ,
            'email' => 'required|email' ,
            'nickname' => 'required' ,
            'degrees' => 'required' ,
            'experience' => 'required' ,
            'institute' => 'required' ,
            'teacher_phone' => 'required' ,
            'date_of_birth' => 'required',
            'password' => 'confirmed|required',
            'profile_picture' => 'image|max:1000'
            );

     public static $ruleswithoutpassword = array(
            'firstname' => 'required' ,
            'lastname' => 'required' ,
            'nickname' => 'required' ,
            'degrees' => 'required' ,
            'experience' => 'required' ,
            'institute' => 'required' ,
            'teacher_phone' => 'required' ,
            'date_of_birth' => 'required',
            'profile_picture' => 'image|max:1000'
            );
     
    public static function teacherList() {
        $teachers = DB::table('users')
            ->join('teachers','users.teachers_id', '=', 'teachers.id')
            ->select('teachers.id',
                'users.firstname',
                'users.lastname',
                'users.nickname',
                'users.email',
                'users.date_of_birth',
                'teachers.experience',
                'teachers.degrees',
                'teachers.institute',
                'teachers.teacher_phone', 
                'teachers.deleted_at',
                'users.deleted_at' ,
                'users.picture')
            ->whereNull('users.deleted_at');          

        return $teachers;
    }

    public static function searchTeacherList($query) {
        $query = trim($query);
        $teachers = DB::table('users')
            ->join('teachers','users.teachers_id', '=', 'teachers.id')
            ->select('teachers.id',
                'users.firstname',
                'users.lastname',
                'users.nickname',
                'users.email',
                'users.date_of_birth',
                'teachers.experience',
                'teachers.degrees',
                'teachers.institute',
                'teachers.teacher_phone', 
                'teachers.deleted_at',
                'users.deleted_at' ,
                'users.picture')
            ->where('users.firstname', 'LIKE', "%$query%")
            ->orWhere('users.lastname', 'LIKE', "%$query%")
            ->orWhere('users.nickname', 'LIKE', "%$query%")
            ->orWhere('teachers.teacher_phone', 'LIKE', "%$query%")
            ->whereNull('users.deleted_at');

        return $teachers;
    }

    public static function scheduleOfTeacher($teachers_id, $date = NULL)
    {
       $schedules = Schedule::_scheduleOfTeacher_Student($teachers_id, null, $date);

       return $schedules; 
    }

    public function scheduleFromNow()
    {
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        $schedules = Schedule::_scheduleOfTeacher_Student($this->user->teachers_id , null, null, $date);

        return $schedules;
    }

    public function user(){
        return $this->hasOne('App\User', 'teachers_id');
    }

    public static function deletedList()
    {
            $teachers = DB::table('users')
            ->join('teachers','users.teachers_id', '=', 'teachers.id')
            ->select('teachers.id as id',
                'users.firstname as firstname',
                'users.lastname as lastname',
                'users.nickname as nickname',
                'users.email as email',
                'users.date_of_birth as date_of_birth',
                'teachers.experience as experience',
                'teachers.degrees as degrees ',
                'teachers.institute as institute ',
                'teachers.teacher_phone as teacher_phone ', 
                'users.picture as picture ')
            ->whereNotNull('users.deleted_at');

        return $teachers;
    }

    
}
