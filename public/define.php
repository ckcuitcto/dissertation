<?php
/**
 * Created by PhpStorm.
 * User: huynh
 * Date: 14-May-18
 * Time: 9:06 PM
 */
define("FILE_IMAGE", array('img', 'jpg', 'png', 'jpeg', 'bmp'), true);

define("FILE_VALID", array('img', 'jpg', 'pdf', 'png', 'jpeg', 'bmp'), true);

define('EXCELLENT',90);
define('VERY_GOOD',80);
define('GOOD',65);
define('AVERAGE',50);
define('POOR',35);
define('BAD',0);

define('ROLE_ADMIN',6);
define('ROLE_PHONGCONGTACSINHVIEN',5);
define('ROLE_BANCHUNHIEMKHOA',4);
define('ROLE_COVANHOCTAP',3);
define('ROLE_BANCANSULOP',2);
define('ROLE_SINHVIEN',1);


define('FORM_STATUS_MARK',1);
define('FORM_STATUS_REMARK',2);
define('FORM_STATUS_FINISH',3);
define('FORM_STATUS_CLOSE',4);

define('USER_ACTIVE',1);
define('USER_INACTIVE',2);

define('HANDLE',1);
define('RESOLVED',2);

define('STUDENT_STUDYING',1); // đang học
define('STUDENT_DEFERMENT',2); // bảo lưu
define('STUDENT_DROP_OUT',3); // bỏ học
define('STUDENT_GRADUATE',4); // tốt nghiệp


define('MALE',1);
define('FEMALE',2);

// trạng thái chấm điểm trong form
// VD: khi = ROLE_SINHVIÊN. nghĩa là sinh viên đã chấm.
// = 0 là chưa chấm.
// -1 là hoàn thành
define('MARK_STATUS_STUDENT',1);
define('MARK_STATUS_BANCANSULOP',2);
define('MARK_STATUS_COVANHOCTAP',4);
define('MARK_STATUS_BANCHUNHIEMKHOA',8);
define('MARK_STATUS_PHONGCONGTACSINHVIEN',16);
define('MARK_STATUS',array(1,2,4,8,16));

define('STUDENT_PATH','upload/student_tmp/');
define('STUDENT_PATH_STORE','upload/student/');
define('STUDENT_LIST_EACH_SEMESTER_PATH','upload/student_list_each_semester/');

define('EVALUATION_CRITERIAS_CHILD_PARENT_1',array(6,7,8));
define('YTHUCTHAMGIAHOCTAP_ID',1);

define('ARRAY_EVALUATION_CRITERIAS_VALIDATE',array(11,12,13,14,15,16,17,18,1,22,23,19,20,21,2,25,26,24,27,28,29,3,30,31,32,33,34,35,4,36,37,38,5));