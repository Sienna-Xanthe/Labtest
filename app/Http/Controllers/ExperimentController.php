<?php

namespace App\Http\Controllers;

use App\Models\Completion;
use App\Models\Student;

use Illuminate\Http\Request;
use Mpdf;

class ExperimentController extends Controller
{
    public function student(Request $request)
    {

        $student_name = $request['student_name'];
        $student_level = $request['student_level'];
        $student_spec = $request['student_spec'];
        $student_year = $request['student_year'];
        $student_class = $request['student_class'];
        $student_num = $request['student_num'];
        $experiment_name = $request['experiment_name'];
        $course_name = $request['course_name'];
        $student_date = $request['student_date'];
        $student_teacher = $request['student_teacher'];

        $res = Student::establish($student_name,$student_level, $student_spec,$student_year, $student_num, $student_class, $experiment_name,$course_name,$student_date,$student_teacher);

        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }


    public function completion(Request $request)
    {

        $completion_1 = $request['completion_1'];
        $completion_2 = $request['completion_2'];
        $completion_3 = $request['completion_3'];
        $completion_4 = $request['completion_4'];
        $completion_5 = $request['completion_5'];
        $completion_6 = $request['completion_6'];
        $completion_l1 = $request['completion_l1'];
        $completion_l2 = $request['completion_l2'];
        $completion_l3 = $request['completion_l3'];
        $completion_m = $request['completion_m'];
        $completion_d = $request['completion_d'];
        $completion_xz1 = $request['completion_xz1'];
        $completion_xz2 = $request['completion_xz2'];
        $completion_pd1 = $request['completion_pd1'];
        $completion_pd2 = $request['completion_pd2'];
        $completion_pd3 = $request['completion_pd3'];
        $grade_xp = $request['grade_xp'];
        $student_id = $request['student_id'];




        $res1 = Completion::establish(
            $completion_1,
            $completion_2,
            $completion_3,
            $completion_4,
            $completion_5,
            $completion_6,
            $completion_l1,
            $completion_l2,
            $completion_l3,
            $completion_m,
            $completion_d,
            $completion_xz1,
            $completion_xz2,
            $completion_pd1,
            $completion_pd2,
            $completion_pd3,
            $student_id
        );

        $grade = 0;

        if (strlen(substr(strrchr($completion_1,"."),1)) == 3) {
            $grade += 5;
           
        }
        if ((strlen($completion_2 - (int)$completion_2) - 2) == 3) {
            $grade += 5;
           
        }
        if ((strlen($completion_3 - (int)$completion_3) - 2) == 3) {
            $grade += 5;
           
        }
        if ((strlen($completion_4 - (int)$completion_4) - 2) == 3) {
            $grade += 5;
           
        }
        if ((strlen($completion_5 - (int)$completion_5) - 2) == 3) {
            $grade += 5;
            
        }
        if ((strlen($completion_6 - (int)$completion_6) - 2) == 3) {
            $grade += 5;
           
        }
        if ($completion_l1 == sprintf("%.3f",($completion_4 - $completion_1))) {
            $grade += 5;
            
        }
        if ($completion_l2 == sprintf("%.3f",($completion_5 - $completion_2))) {
            $grade += 5;
            
        }
        if ($completion_l3 == sprintf("%.3f",($completion_6 - $completion_3))) {
            $grade += 5;
            
        }
        $ls = $completion_l1 + $completion_l2 + $completion_l3;
        if($ls == sprintf("%.3f",($completion_4 - $completion_1))+sprintf("%.3f",($completion_5 - $completion_2))+sprintf("%.3f",($completion_6 - $completion_3))){
            if ($completion_m == sprintf("%.3f",(180 / $ls))) {
                $grade += 5;
                
            }
            if ($completion_d == sprintf("%.3f", 0.000589 * $completion_m * 0.5)) {
                $grade += 20;
               
            }
        }
        


        
        $grade = $grade + $grade_xp;



        $res2 = Student::grade($student_id, $grade,$grade_xp);




        $res['res1'] = $res1;
        $res['res2'] = $res2;

        return $res ?
            json_success('操作成功!', null, 200) :
            json_fail('操作失败!', null, 100);
    }

    public function pdf(Request $request)
    {


        $student_id = $request['student_id'];
  

        $student_a = Student::show($student_id);

        $student_b = json_decode($student_a);
        $completion_1 = $student_b[0]->completion_1;
        $completion_2 = $student_b[0]->completion_2;
        $completion_3 = $student_b[0]->completion_3;
        $completion_4 = $student_b[0]->completion_4;
        $completion_5 = $student_b[0]->completion_5;
        $completion_6 = $student_b[0]->completion_6;
        $completion_l1 = $student_b[0]->completion_l1;
        $completion_l2 = $student_b[0]->completion_l2;
        $completion_l3 = $student_b[0]->completion_l3;
        $completion_m = $student_b[0]->completion_m;
        $completion_d = $student_b[0]->completion_d;
        $completion_xz1 = $student_b[0]->completion_xz1;
        $completion_xz2 = $student_b[0]->completion_xz1;
        $completion_pd1 = $student_b[0]->completion_pd1;
        $completion_pd2 = $student_b[0]->completion_pd2;
        $completion_pd3 = $student_b[0]->completion_pd3;

        



        $student_name = $student_b[0]->student_name;
        $student_level = $student_b[0]->student_level;
        $student_spec = $student_b[0]->student_spec;
        $student_year = $student_b[0]->student_year;
        $student_class = $student_b[0]->student_class;
        $student_num = $student_b[0]->student_num;
        $experiment_name = $student_b[0]->experiment_name;
        $course_name = $student_b[0]->course_name;
        $student_date = $student_b[0]->student_date;
        $student_teacher = $student_b[0]->student_teacher;
        $grade = $student_b[0]->grade;
        $grade_xp = $student_b[0]->grade_xp;




        $res = view('invoice', [
            'name' => $student_name,
            'student_level' => $student_level,
            'student_spec' => $student_spec,
            'student_year' => $student_year,
            'experiment_name' => $experiment_name,
            'course_name' => $course_name,
            'student_date' => $student_date,
            'student_teacher' => $student_teacher,
            'student_num' => $student_num,
            'student_class' => $student_class,
            'grade' => $grade,
            'grade_xp' => $grade_xp,
            'grade_tk' => ($grade - $grade_xp),
             


            'completion_1' => sprintf("%.3f",$completion_1),
            'completion_2' => sprintf("%.3f",$completion_2),
            'completion_3' => sprintf("%.3f",$completion_3),
            'completion_4' => sprintf("%.3f",$completion_4),
            'completion_5' => sprintf("%.3f",$completion_5),
            'completion_6' => sprintf("%.3f",$completion_6),
            'completion_l1' => sprintf("%.3f",$completion_l1),
            'completion_l2' => sprintf("%.3f",$completion_l2),
            'completion_l3' => sprintf("%.3f",$completion_l3),
            'completion_m' => sprintf("%.3f",$completion_m),
            'completion_d' => sprintf("%.3f",$completion_d),
            'completion_xz1' => sprintf("%.3f",$completion_xz1),
            'completion_xz2' => sprintf("%.3f",$completion_xz2),
            'completion_pd1' => sprintf("%.3f",$completion_pd1),
            'completion_pd2' => sprintf("%.3f",$completion_pd2),
            'completion_pd3' => sprintf("%.3f",$completion_pd3),

        ]);



        $mpdf = new Mpdf\Mpdf(['utf-8', 'A4', 16, '', 10, 10, 15, 15]);
        
    
        $mpdf->showImageErrors = true;

        $mpdf->WriteHTML($res);

        $mpdf->Output('实验报告.pdf', "I");

        exit;
    }
}
