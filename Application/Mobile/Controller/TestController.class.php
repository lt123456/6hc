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

    public function record() {
        $str = '     <tr>
                <td>
                    121期<br />2016-10-20
                </td>
                <td class="pl_17 numbre">
                        <div data-text="虎"  class="data"><span class="red">07</span><b>虎</b></div>
                        <div data-text="鼠"  class="data"><span class="red">45</span><b>鼠</b></div>
                        <div data-text="蛇"  class="data"><span class="green">28</span><b>蛇</b></div>
                        <div data-text="羊"  class="data"><span class="red">02</span><b>羊</b></div>
                        <div data-text="马"  class="data"><span class="blue">15</span><b>马</b></div>
                        <div data-text="蛇"  class="data"><span class="blue">04</span><b>蛇</b></div>

                </td>
                <td class="numbre">
                    <div data-text="虎"  class="data"><span class="red">19</span><b>虎</b></div>
                </td>
                <td>
                        <p>单</p>
                </td>
                <td>
小                </td>
                <td>
                    双
                    (120)
                </td>
                <td>
小                </td>
            </tr> ';
        $res = '/.*<tr>.*<td>(.*)期<br \/>(.*)<\/td>.*/';
//            $res = '/(.*)/';
        preg_match_all($res,$str,$matchs);

        print_r($matchs);
//        echo $str;
    }
}
