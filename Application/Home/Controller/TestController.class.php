<?php
namespace Home\Controller;

//use Think\Controller;

class TestController extends BaseController
{


    protected $animal_record;
    protected $special_record;

    public function __construct()
    {
        parent::__construct();

        $this->animal_record = D('animal_record');
        $this->special_record = D('special_record');

    }


    public function addAnimalRecord()
    {
        for($i=1;$i<=100;$i++) {
            $data['user_id'] = rand(1, 3);
            $data['periods_id'] = 11;
            $data['week'] = date('W')-1;
            $data['created_at'] = date('Y-m-d',strtotime('2016-11-05 11:12:10'));
            $data['recomm_animals'] = '狗,猪,牛';
            $data['recomm_sum'] = 3;
            $data['price_sum'] = 1;
            $data['status'] = 'prize';

            $this->animal_record->add($data);
        }
    }

    public function addSpecialRecord()
    {

        for($i=1;$i<=100;$i++) {
            $data['user_id'] = rand(1, 3);
            $data['periods_id'] = 11;
            $data['week'] = date('W')-1;
            $data['created_at'] = date('Y-m-d',strtotime('2016-11-05 11:12:10'));
            $data['recomm_special'] = rand(1,49).','.rand(1,49).','.rand(1,49).','.rand(1,49);
            $data['recomm_sum'] = 4;
            $data['price_sum'] = 1;
            $data['status'] = 'prize';
            $data['created_at'] = date('Y-m-d',time());
            $this->special_record->add($data);
        }
    }
}
