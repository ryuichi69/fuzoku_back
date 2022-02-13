<?php
App::uses('AppController', 'Controller');
class SarariesController extends AppController {

//CERF対策    
public $components = array(
'Security' => array('validatePost' => false), 
);
    
public $uses = array('Driver','Car','Shop','Sarary');

//ここに各ページの表示に関わる部分などを入力
	public function input() {
	$this->autoLayout = false;//この部分で管理画面を外す。

	//DBの結果を表示するのにはこの2点が必要
        //ここは後々で店舗別に出力する必要有り
	$driver = $this->Driver->find('all');
       	$shop = $this->Shop->find('all');
        $car = $this->Car->find('all');
        $start_day = date('Y/m/d');
        $end_day = date('Y/m/d',strtotime('+1day'));
        $options = array(
                    'conditions' => array(
                        'syuko_day BETWEEN ? AND ?' => array($start_day,$end_day),
                    )
        );
        $sarary = $this->Sarary->find('all',$options);

        $shop_name = array();
        $car_name = array();
        $driver_name = array();
        $total =0;
        
        foreach($shop as $val){
            array_push($shop_name,$val['Shop']['name']);
        }

        foreach($car as $val){
            array_push($car_name,$val['Car']['name']);
        }

        foreach($driver as $val){
            array_push($driver_name,$val['Driver']['name']);
        }

        $this->set('car_name',$car_name);
        $this->set('shop_name',$shop_name);
	$this->set('driver_name',$driver_name);        
        $this->set('sarary',$sarary);
   //     $this->render('input');
	}

        //出力する画面
 	public function output() {
	$this->autoLayout = false;//この部分で管理画面を外す。
	$driver = $this->Driver->find('all');        
        $car = $this->Car->find('all');
        /*合計算出用のql文
         * 速度より実装のやりやすさを重視して、別SQL文で実行 
         */
        if(isset($this->request->data['Sararies'])){
        $sql = 'select ';
        $sql .= 'sum(eigyou_number),';
        $sql .= 'sum(jigo_number),';        
        $sql .= 'sum(shouhi_number),';
        $sql .= 'sum(unchin_total_cost),';
        $sql .= 'sum(trouble_number),';        
        $sql .= 'sum(trouble_people),';
        $sql .= 'sum(waribiki_cost),';        
        $sql .= 'sum(taxin_cost),';
        $sql .= 'sum(ticket_cost),';
        $sql .= 'sum(fukushi_cost),';
        $sql .= 'sum(fukushi_number),';
        $sql .= 'sum(actual_cost),';
        $sql .= 'sum(yusou_number),';
        $sql .= 'sum(jissya_distance),';
        $sql .= 'sum(running_distance),';        
        $sql .= 'sum(jissya_distance)/sum(running_distance),';        
        $sql .= 'sum(fuel),';        
        $sql .= 'sum(musen_number),';
        $sql .= 'sum(mizuage),';        
        $sql .= 'sum(tax),';
        $sql .= 'avg(eigyou_number),';
        $sql .= 'avg(jigo_number),';        
        $sql .= 'avg(shouhi_number),';
        $sql .= 'avg(unchin_total_cost),';
        $sql .= 'avg(trouble_number),';        
        $sql .= 'avg(trouble_people),';
        $sql .= 'avg(waribiki_cost),';        
        $sql .= 'avg(taxin_cost),';
        $sql .= 'avg(ticket_cost),';
        $sql .= 'avg(fukushi_cost),';
        $sql .= 'avg(fukushi_number),';
        $sql .= 'avg(actual_cost),';
        $sql .= 'avg(yusou_number),';
        $sql .= 'avg(jissya_distance),';
        $sql .= 'avg(running_distance),';        
        $sql .= 'avg(jissya_distance)/sum(running_distance),';        
        $sql .= 'avg(fuel),';        
        $sql .= 'avg(musen_number),';
        $sql .= 'avg(mizuage),';        
        $sql .= 'avg(tax)';
        $sql .= ' from sararies where ';        
        $sql .='syuko_day between ';
        if(@$this->request->data['Sararies']['search'] == 1){
        $sql.='"';            
        $sql.=$this->request->data['Sararies']['start_day'].' 00:00:00';
        $sql.='"';        
        $sql.=' and ';
        $sql.='"';
        $sql.=$this->request->data['Sararies']['end_day'].' 00:00:00';
        $sql.='"';
        $sql .= ' AND driver_id = ';
        $sql .= $this->request->data['Sararies']['driver_id']+1;
        }else if(@$this->request->data['Sararies']['search'] == 2){
        $sql.='"';            
        $sql.=$this->request->data['Sararies']['start_day2'].' 00:00:00';
        $sql.='"';        
        $sql.=' and ';
        $sql.='"';        
        $sql.=$this->request->data['Sararies']['end_day2'].' 00:00:00';
        $sql.='"';        
        $sql .= ' AND car_id = ';
        $sql .= $this->request->data['Sararies']['car_id']+1;
        }
        
        $total = $this->Sarary->query($sql);
        } 
        $car_name = array();
        $driver_name = array();

        //ドライバー別に検索
        if(@$this->request->data['Sararies']['search'] == 1){
        $start_day = $this->request->data['Sararies']['start_day'];
        $end_day = $this->request->data['Sararies']['end_day'];//1日ずらす必要有り
        $driver_id = $this->request->data['Sararies']['driver_id']+1;
        $options = array(
                    'conditions' => array(
                        'syuko_day BETWEEN ? AND ?' => array($start_day,$end_day),
                        'driver_id' => $driver_id
                    )
        );
            $sarary = $this->Sarary->find('all',$options);
        }else if(@$this->request->data['Sararies']['search'] == 2){
        $start_day = $this->request->data['Sararies']['start_day2'];
        $end_day = $this->request->data['Sararies']['end_day2'];//1日ずらす必要有り
        $car_id = $this->request->data['Sararies']['car_id']+1;
        $options = array(
                    'conditions' => array(
                        'syuko_day BETWEEN ? AND ?' => array($start_day,$end_day),
                        'car_id' => $car_id
                    )
        );
            $sarary = $this->Sarary->find('all',$options);
        }else{
                   $start_day = date('Y/m/d');
                   $end_day = date('Y/m/d',strtotime('+1day'));
            $options = array(
                    'conditions' => array(
                        'syuko_day BETWEEN ? AND ?' => array($start_day,$end_day),
                    )
        );
            $sarary = $this->Sarary->find('all',$options);                
        }
        
        foreach($driver as $val){
            array_push($driver_name,$val['Driver']['name']);
        }

        foreach($car as $val){
            array_push($car_name,$val['Car']['name']);
        }

        $this->set('car_name',$car_name);        
	$this->set('driver_name',$driver_name);
        $this->set('sarary',$sarary);
        $this->set('total',@$total);        
 //       $this->render('output');
	}       
        //追加用のアカウント(add)
        public function add(){
            //セーブ不可能な場合
            $geisya_cost = 710;//迎車代
            $jigo_odds = 90;//爾後率
            $tax_ratio = 5;//税率
            $mizuage_ratio = 100/(100 + $tax_ratio);
            $output_tax_ratio = 1 - $mizuage_ratio;
            //入力されてないときの処理は後回し
            $eigyou_number = ($this->request->data['Sarary']['eigyou_number'] != null) ? $this->request->data['Sarary']['eigyou_number'] : 0;
            $jigo_number = ($this->request->data['Sarary']['jigo_number'] != null) ? $this->request->data['Sarary']['jigo_number'] : 0;
            $waribiki_cost = ($this->request->data['Sarary']['waribiki_cost'] != null) ? $this->request->data['Sarary']['waribiki_cost'] : 0;
            $ticket_cost = ($this->request->data['Sarary']['ticket_cost'] != null) ? $this->request->data['Sarary']['ticket_cost'] : 0;
            $fukushi_cost = ($this->request->data['Sarary']['fukushi_cost'] != null) ? $this->request->data['Sarary']['fukushi_cost'] : 0; 
            $jissya_distance = ($this->request->data['Sarary']['jissya_distance'] != null) ? $this->request->data['Sarary']['jissya_distance'] : 0;
            $running_distance = ($this->request->data['Sarary']['running_distance'] != null) ? $this->request->data['Sarary']['running_distance'] : 0;
            $jissya_percent = round(($jissya_distance / $running_distance),2);            
            $unchin_total_cost = $eigyou_number * $geisya_cost + $jigo_number * $jigo_odds;//運賃合計 = 営業回数* 迎車代+ 爾後回数 * 爾後回数に基づく倍率(90)
            $taxin_cost = $unchin_total_cost - $waribiki_cost;//税込運賃　= 運賃合計 - 割引料金
            $actual_cost = $taxin_cost - $ticket_cost - $fukushi_cost;//現金 = 税込運賃　- チケット代 - 福祉券
            $mizuage = $unchin_total_cost * $mizuage_ratio;//税抜水揚 = (運賃合計) * ( 1 - 税率 )
            $mizuage = ceil($mizuage /10)*10;//1の位を四捨五入(10で割った物を小数点で切り上げして10倍)
            $tax = $unchin_total_cost - $mizuage;//消費税 = 運賃合計 - 税抜水揚として計算
            
            if($this->request->is('post')){
            $data = array(
                'driver_id' => $this->request->data['Sarary']['driver_id']+1,
                'car_id' => $this->request->data['Sarary']['car_id']+1,
//                'shop_id' => $this->request->data['Sarary']['shop_id']+1,
                'syuko_day' => $this->request->data['Sarary']['syuko_day'],
                'eigyou_number' => $this->request->data['Sarary']['eigyou_number'],
                'jigo_number' => $this->request->data['Sarary']['jigo_number'],
                'shouhi_number' => $this->request->data['Sarary']['shouhi_number'],
                'unchin_total_cost' => $unchin_total_cost,
                'trouble_number' => $this->request->data['Sarary']['trouble_number'], 
                'trouble_people' => $this->request->data['Sarary']['trouble_people'],                
                'waribiki_cost' => $this->request->data['Sarary']['waribiki_cost'],                
                'taxin_cost' => $taxin_cost,                
                'ticket_cost' => $this->request->data['Sarary']['ticket_cost'],
                'fukushi_cost' => $this->request->data['Sarary']['fukushi_cost'],
                'fukushi_number' => $this->request->data['Sarary']['fukushi_number'],
                'actual_cost' => $actual_cost,
                'yusou_number' => $this->request->data['Sarary']['yusou_number'],
                'jissya_distance' => $this->request->data['Sarary']['jissya_distance'],
                'running_distance' => $this->request->data['Sarary']['running_distance'],                
                'jissya_percent' => $jissya_percent,
                'fuel' => $this->request->data['Sarary']['fuel'],
                'musen_number' => $this->request->data['Sarary']['musen_number'],
                'mizuage' => $mizuage,
                'tax' => $tax,
                );
            }
                        
            $this->Sarary->begin();
            if(!$this->Sarary->save($data)){
                throw new Exception('入力に失敗しました。お手数ですがもう一度ご入力下さい。');
                $this->Sarary->rollback(); 
            }
                $this->Sarary->commit(); 
                $this->redirect('/Sararies/input');
        }

        //削除用のアカウント(delete)
        public function delete(){
            //セーブ不可能な場合
            if($this->request->is('post')){
            $id = $this->request->data['Sarary']['del_id'];
            $sql = "DELETE FROM sararies WHERE id = ".$id;
            
            $this->Sarary->query($sql);
            }
            $this->redirect('/Sararies/input');
        }
}