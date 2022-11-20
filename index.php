<?php

class КЛАСС_ПЛАГИНА extends Plugin
{
    public function init()
    {
        parent::init();

        $this->setSettings(array(
            'extension_id'   => 'ИДЕНТИФИКАТОР РАСШИРЕНИЯ СГЕНЕРИРОВАННЫЙ TAMARANGA FL',
            'plugin_title'   => 'API',
            'plugin_version' => '1.0.0',
        ));

        /**
         * Настройки заполняемые в админ. панели
         */
        $this->configSettings(array(
            //
        ));
    }

    //переопределение роутов
    protected function start()
    {
        $this->routeAdd('api/routes', array(
            'pattern' => 'api/(.*)',
            'callback' => $this->routeAction('$1')
        ));
    }

    public function listorders()
    {
	$aFilter['status'] = 3;
	$response = Orders::model()->ordersList($aFilter);
	$this->log('Вызов метода ListOrders из приложения: ', Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function orderinfo()
    {
	$orderid = $this->input->get('orderid');
	$response = Orders::model()->orderDataView($orderid);
	$this->log('Вызов метода OrderInfo из приложения: '.$orderid, Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function orderoffers()
    {
	$orderid = $this->input->get('orderid');
	$response = Orders::model()->orderOffers($orderid);
	$this->log('Вызов метода OrderOffers из приложения: '.$orderid, Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function offersaveapp()
    {
	$postData = file_get_contents('php://input');
	//$zapros = json_decode($postData, true);
	$aData = array();
	$aData['order_id'] = $this->input->post('orderid');
	$aData['user_id'] = $this->input->post('userid');
	$aData['price_to'] = $this->input->post('offprice');
	$aData['price_curr'] = '2';
	$aData['terms_from'] = $this->input->post('orderdate');
	$aData['terms_to'] = $this->input->post('orderperiod');
	$aData['client_only'] = '1';
	$aData['fairplay'] = '1';
	$aData['is_new'] = '1';
	$response['id'] = Orders::model()->offerSave('', $aData);
	if($response['id']>0){
	    $this->db->update('bff_orders', ['offers_cnt = offers_cnt + 1'], ['id'=>$this->input->post('orderid')]);
	}
	$this->log('Вызов метода OfferSave из приложения: '. $postData, Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function listmyorders()
    {
	$aFilter['user_id'] = $this->input->get('userid');
	$st = $this->input->get('st');
	if($st==2){
	    $aFilter['status'] = 0;
	}
	elseif($st==3){
	    $aFilter['status'] = array(
		1,
		4,
		5,
	    );
	}
	elseif($st==4){
	    $aFilter['status'] = 3;
	}
	$response = Orders::model()->ordersListRespondent($aFilter, false);
	$this->log('Вызов метода ListMyOrders из приложения: '. $aFilter['user_id'], Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function mwbo()
    {
	$orderid = $this->input->get('orderid');
	$type = $this->input->get('type');
	$userid = $this->input->get('userid');
	if($type==1){
	    $response = $this->db->one_data('SELECT id FROM bff_fairplay_workflows WHERE order_id = :order_id', [':order_id' => $orderid]);
	}
	elseif($type==2){
	    $response = $this->db->one_data('SELECT status FROM bff_orders_offers WHERE order_id = :order_id AND user_id = :user_id', [':order_id' => $orderid, ':user_id' => $userid]);
	}
	$this->log('Вызов метода MyWorkflowByOrder из приложения: '.$orderid, Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function wflist()
    {
	$userid = $this->input->get('userid');
	$fil['user'] = $userid;
	$response = Fairplay::model()->workflowList($fil);
	$this->log('Вызов метода WorkflowList из приложения: '.$userid, Logger::INFO);
	$this->ajaxResponse($response);
    }

    public function wfinfo()
    {
	$wfid = $this->input->get('wfid');
	$fil['id'] = $wfid;
	$response = Fairplay::model()->workflowData($fil);
	$this->log('Вызов метода WorkflowInfo из приложения: '.$wfid, Logger::INFO);
	$this->ajaxResponse($response);
    }
    
    public function wfst()
    {
        $workflowID = $this->input->post('wfid');
	$fil['id'] = $workflowID;
        $data = Fairplay::model()->workflowData($workflowID, array('id', 'status', 'worker_id', 'client_id'));
	# переводим ход работы в статус "Загрузка фото первого этажа до уборки"
	Fairplay::model()->workflowSave($workflowID, array(
		'status' => 11,
	));
	# помечаем в истории
        Fairplay::model()->historyAdd($workflowID, array(
            'type'    => 16,
            'user_id' => $data['client_id'],
            'created'   => $this->db->now(),
        ));
        $this->db->insert('bff_fairplay_history', ['workflows_id'=>$workflowID, 'type'=>'start_work', 'longitude'=>$this->input->post('long'), 'latitude'=>$this->input->post('lat'), 'datezap'=>$this->db->now()]);
        $this->db->insert('bff_fairplay_workflows_chat', ['type'=>'2', 'workflow_id'=>$workflowID, 'author_id'=>$data['worker_id'], 'created'=>$this->db->now()]);
        $this->log('Вызов метода WorkflowStart из приложения: '.$workflowID, Logger::INFO);
        $response = Fairplay::model()->workflowData($fil);
        $this->ajaxResponse($response);
    }
    
    public function wfsw()
    {
	//$postData = file_get_contents('php://input');
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']).'files/orders/';
	$workflowID = $this->input->post('sid');
	$typehome = $this->input->post('typehome');
	$file = $this->input->post('file');
	$fil['id'] = $workflowID;
	$stw = $this->input->post('stw');
	$name = $this->input->post('name');
	$act = $this->input->post('act');
	$ext = end((explode(".", basename($name))));
	$files = $workflowID . base64_encode(basename($name)) . $stw . '.' . $ext;
	$uploadfile = $uploaddir . $files;
	$this->log('Вызов метода WorkflowSaveWork из приложения: '.$name.' - '.$files.' - '.$uploadfile, Logger::INFO);
	$fp = fopen($uploadfile, "w+");
	fwrite($fp, base64_decode($file));
	fclose($fp);
    if (filesize($uploadfile) != 0) {
        if($act=='send1'){
            $this->db->insert('bff_fairplay_history', ['workflows_id'=>$workflowID, 'type'=>'first_floor_to', 'file'=>bff::urlBase().'files/orders/'.$files, 'datezap'=>$this->db->now()]);
            Fairplay::model()->workflowSave($workflowID, array(
                'status' => 12,
            ));
            $response = true;
            $this->ajaxResponse($response);
        }
        elseif($act=='send2'){
            $this->db->insert('bff_fairplay_history', ['workflows_id'=>$workflowID, 'type'=>'last_floor_after', 'file'=>bff::urlBase().'files/orders/'.$files, 'datezap'=>$this->db->now()]);
            if($typehome==1){
			    Fairplay::model()->workflowSave($_POST['sid'], array(
				    'status' => 14,
			    ));
			}
			elseif($typehome==2){
			    Fairplay::model()->workflowSave($_POST['sid'], array(
				    'status' => 13,
			    ));
			}
            $response = true;
            $this->ajaxResponse($response);
        }
        elseif($act=='send3'){
            $this->db->insert('bff_fairplay_history', ['workflows_id'=>$workflowID, 'type'=>'elevator_car', 'file'=>bff::urlBase().'files/orders/'.$files, 'datezap'=>$this->db->now()]);
            Fairplay::model()->workflowSave($workflowID, array(
                'status' => 14,
            ));
            $response = true;
            $this->ajaxResponse($response);
        }
        elseif($act=='send4'){
            $this->db->insert('bff_fairplay_history', ['workflows_id'=>$workflowID, 'type'=>'first_floor_after', 'file'=>bff::urlBase().'files/orders/'.$files, 'datezap'=>$this->db->now()]);
            Fairplay::model()->workflowSave($workflowID, array(
                'status' => 9,
            ));
            $ImageDataSet = array();
            $RequestID = $this->db->one_data('SELECT a.requestid FROM bff_orders a LEFT JOIN bff_fairplay_workflows b ON b.order_id = a.id WHERE b.id = :id', [':id'=>$workflowID]);
            $EntNum = $this->db->one_data('SELECT a.entnum FROM bff_orders a LEFT JOIN bff_fairplay_workflows b ON b.order_id = a.id WHERE b.id = :id', [':id'=>$workflowID]);
            $masshist = $this->db->select('SELECT * FROM bff_fairplay_history WHERE workflows_id = :id', [':id'=>$workflowID]);
            $countmasshist = count($masshist);
            for($hgf=0;$hgf<$countmasshist;$hgf++){
                if($masshist[$hgf]['type'] == 'start_work'){
                    $ExecutingDateStart = date("Y-m-d", strtotime($masshist[$hgf]['datezap']));
                    $ExecutingTimeStart = date("H:i:s", strtotime($masshist[$hgf]['datezap']));
                    $ExecutingEndLong = $masshist[$hgf]['longitude'];
                    $ExecutingEndLat = $masshist[$hgf]['latitude'];
                }
                elseif($masshist[$hgf]['type'] == 'first_floor_to'){
                    $filebd = $masshist[$hgf]['file'];
                    $filebdname = str_replace(bff::urlBase().'files/orders/', '', $filebd);
                    $erfile = $uploaddir . $filebdname;
                    $dataerfile = file_get_contents($erfile);
                    $ImageData = base64_encode($dataerfile);
                    $ImageType = 1001;
                    $fft = array(
                        'ImageType' => $ImageType,
                        'ImageData' => $ImageData
                        );
                    array_push($ImageDataSet, $fft);
                }
                elseif($masshist[$hgf]['type'] == 'last_floor_after'){
                    $filebd = $masshist[$hgf]['file'];
                    $filebdname = str_replace(bff::urlBase().'files/orders/', '', $filebd);
                    $erfile = $uploaddir . $filebdname;
                    $dataerfile = file_get_contents($erfile);
                    $ImageData = base64_encode($dataerfile);
                    $ImageType = 1002;
                    $lfa = array(
                        'ImageType' => $ImageType,
                        'ImageData' => $ImageData
                        );
                    array_push($ImageDataSet, $lfa);
                }
                elseif($masshist[$hgf]['type'] == 'first_floor_after'){
                    $filebd = $masshist[$hgf]['file'];
                    $filebdname = str_replace(bff::urlBase().'files/orders/', '', $filebd);
                    $erfile = $uploaddir . $filebdname;
                    $dataerfile = file_get_contents($erfile);
                    $ImageData = base64_encode($dataerfile);
                    $ImageType = 1003;
                    $ffa = array(
                        'ImageType' => $ImageType,
                        'ImageData' => $ImageData
                        );
                    array_push($ImageDataSet, $ffa);
                    $ExecutingDateEnd = date("Y-m-d", strtotime($masshist[$hgf]['datezap']));
                    $ExecutingTimeEnd = date("H:i:s", strtotime($masshist[$hgf]['datezap']));
                }
                elseif($masshist[$hgf]['type'] == 'elevator_car'){
                    $filebd = $masshist[$hgf]['file'];
                    $filebdname = str_replace(bff::urlBase().'files/orders/', '', $filebd);
                    $erfile = $uploaddir . $filebdname;
                    $dataerfile = file_get_contents($erfile);
                    $ImageData = base64_encode($dataerfile);
                    $ImageType = 1003;
                    $ec = array(
                        'ImageType' => $ImageType,
                        'ImageData' => $ImageData
                        );
                    array_push($ImageDataSet, $ec);
                }
            }
            $response = true;
            $this->ajaxResponse($response);
        }
    }
    else{
        $response = false;
        $this->ajaxResponse($response);
    }
    }
    
    public function wfse()
    {
	$total_price = $this->input->post('total_price');
	$sid = $this->input->post('sid');
	$total_grade = $this->input->post('total_grade');
	#обновляем стоимость в базе
	$this->db->update('bff_fairplay_workflows', ['price' => $total_price], ['id'=>$sid]);
	#переводим заявку в статус Выплата
	Fairplay::model()->workflowSave($sid, array(
	    'status' => 6,
	));
	#фиксируем в истории статус Выплата
	$datarr1 = Fairplay::model()->workflowData($sid, array('id', 'status', 'worker_id', 'client_id', 'order_id'));
	Fairplay::model()->historyAdd($sid, array(
	    'type'    => 5,
	    'user_id' => $datarr1['client_id'],
	    'created'   => $this->db->now(),
	));
	#проверяем новичок пользователь или нет
	$datarr2 = $this->db->one_data('SELECT COUNT(id)
	    FROM bff_fairplay_workflows
	    WHERE worker_id = ' . $datarr1['worker_id'] . ' AND status = 4'
	);
	#берем название заяки
	$title_work = $this->db->one_data('SELECT title
	    FROM bff_orders
	    WHERE id = ' . $datarr1['order_id'] . ' '
	);
	#берем текущий рейтинг
	$tek_rating = $this->db->one_data('SELECT rating
	    FROM bff_users
	    WHERE user_id = ' . $datarr1['worker_id'] . ''
	);
	#если не новичок
	if($datarr2 > 0){
	    $new_rating = ($tek_rating + $total_grade)/2;
	}
	#если новичок
	else{
	    $new_rating = $total_grade;
	}
	#сохраняем новый рейтинг пользователя
	$this->db->update('bff_users', ['rating' => $new_rating], ['user_id'=>$datarr1['worker_id']]);
	#берем старый баланс
	$old_bal = $this->db->one_data('SELECT balance
	    FROM bff_users
	    WHERE user_id = ' . $datarr1['worker_id'] . ''
	);
	#обновляем баланс пользователя
	$binbd = round(($old_bal + $total_price), 2);
	$this->db->update('bff_users', ['balance' => $binbd], ['user_id'=>$datarr1['worker_id']]);
	#берем новый баланс
	$new_bal = $this->db->one_data('SELECT balance
	    FROM bff_users
	    WHERE user_id = ' . $datarr1['worker_id'] . ''
	);
	#фиксируем счет о выплате в системе
	$this->db->insert('bff_bills', ['user_id'=>$datarr1['worker_id'], 'user_balance'=>$new_bal, 'type'=>2, 'amount'=>$total_price, 'currency_id'=>2, 'created'=>$this->db->now(), 'payed'=>$this->db->now(), 'status'=>2, 'description'=>'Выплата за выполнение работы по заявке "'.$title_work.'"']);
	#фиксируем отзыв Заказчика
	$otzid = $this->db->insert('bff_opinions', ['author_id'=>$datarr1['client_id'], 'user_id'=>$datarr1['worker_id'], 'order_id'=>$datarr1['order_id'], 'type'=>3, 'status'=>1, 'message'=>'Все выполнено нормально.', 'moderated'=>0, 'created'=>$this->db->now(), 'modified'=>$this->db->now()]);
	#переводим заявку в статус Выполнена
	Fairplay::model()->workflowSave($sid, array(
	    'status' => 4,
	    'client_opinion' => $otzid,
	    'extra' => 'gr='.$total_grade.',pr='.$total_price,
	));
	#фиксируем в истории статус Выполнена
	Fairplay::model()->historyAdd($sid, array(
	    'type'    => 3,
	    'user_id' => $datarr1['client_id'],
	    'created'   => $this->db->now(),
	));
	#формируем документы
	Fairplay::workflowsDocs($sid)->makeSingle($datarr1['client_id'], 1);
	Fairplay::workflowsDocs($sid)->makeSingle($datarr1['worker_id'], 1);
	#фиксируем в чате статус Выполнена
	$this->db->insert('bff_fairplay_workflows_chat', ['type'=>3, 'workflow_id'=>$sid, 'author_id'=>$datarr1['worker_id'], 'created'=>$this->db->now()]);
	#логируем и возвращаем результат
	$this->log('Вызов метода WorkflowSaveEvaluation из приложения: '.$sid, Logger::INFO);
	$response = true;
	$this->ajaxResponse($response);
    }
    
    public function userbalance()
    {
        $userid = $this->input->get('userid');
        $keys = array('balance');
        $response = Users::model()->userData($userid, $keys);
        $this->log('Вызов метода UserBalance из приложения: '.$userid, Logger::INFO);
        $this->ajaxResponse($response);
    }
    
    public function userinfo()
    {
        $userid = $this->input->get('userid');
        $keys = array('name', 'surname', 'email', 'phone_number');
        $response = Users::model()->userData($userid, $keys);
        $this->log('Вызов метода UserInfo из приложения: '.$userid, Logger::INFO);
        $this->ajaxResponse($response);
    }
    
    public function userbills()
    {
        $userid = $this->input->get('userid');
        $keys = array('user_id' => $userid);
        $response = Bills::model()->billsList($keys, false);
        $this->log('Вызов метода UserBills из приложения: '.$userid, Logger::INFO);
        $this->ajaxResponse($response);
    }
    
    public function savecheck()
    {
	//$postData = file_get_contents('php://input');
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']).'files/bills/';
	$invid = $this->input->post('invid');
	$file = $this->input->post('file');
	$name = $this->input->post('name');
	$ext = end((explode(".", basename($name))));
	$files = $invid . base64_encode(basename($name)) . '.' . $ext;
	$uploadfile = $uploaddir . $files;
	$this->log('Вызов метода SaveCheck из приложения: '.$invid, Logger::INFO);
	$fp = fopen($uploadfile, "w+");
	fwrite($fp, base64_decode($file));
	fclose($fp);
    if (filesize($uploadfile) != 0) {
        $cUpdate['status'] = 5;
        $cUpdate['details'] = bff::urlBase().'files/bills/'.$files;
	    $res = Bills::model()->billSave($invid, $cUpdate);
        $response = true;
        $this->ajaxResponse($response);
    }
    else{
        $response = false;
        $this->ajaxResponse($response);
    }
    }
    
    public function widrfund()
	{
	$userid = $this->input->post('userid');
	$keysv = array('balance');
	//$userbal = Users::model()->userData($userid, $keysv);
	//$userbal = $userbal['data']['balance'];
	$userbal = $this->input->post('balance');
	$amount = $this->input->post('amount');
	$money = $this->input->post('amount');
	if ($money > $userbal) {
		$this->errors->set('Сумма вывода больше, чем есть на счете. Укажите другую сумму.');
		$response = false;
		$this->ajaxResponse($response);
	}
	if ($money < 0) {
		$this->errors->set('Сумма вывода не может быть отрицательной. Укажите другую сумму.');
		$response = false;
		$this->ajaxResponse($response);
	}
	$bFilter = array('user_id' => $userid, 'status' => array(4, 5));
	$bTotal = Bills::model()->billsList($bFilter, true);
	if ($bTotal > 0) {
		$this->errors->set('У вас есть счет на вывод средств, который ожидает загрузки или подтверждения чека модератором.');
		$response = false;
		$this->ajaxResponse($response);
	}
	# 1) проверяем, есть ли не обработанные счета
	$aFilter = array('user_id' => $userid, 'status' => 1);
	$nTotal = Bills::model()->billsList($aFilter, true);
	if ($nTotal > 0) {
		# 2) если есть необработанный счет на вывод, то добавляем деньги в него
		$list = Bills::model()->billsList($aFilter, false);
		foreach($list as $v){
			$schetid = $v['id'];
			$summaA = $v['amount'];
			$summaB = $v['money'];
		}
		$aUpdate['user_balance'] = $userbal - $amount;
		$aUpdate['amount'] = $summaA + $amount;
		$aUpdate['money'] = $summaB + $money;
		$billID = Bills::model()->billSave($schetid, $aUpdate);
		#создаем воркфлоу
		$idwork = $this->db->insert('bff_fairplay_workflows', ['order_id'=>0, 'client_id'=>16, 'worker_id'=>$userid, 'offer_id'=>0, 'fairplay'=>1, 'status'=>4, 'status_prev'=>6, 'arbitrage'=>0, 'created'=>$this->db->now(), 'modified'=>$this->db->now(), 'reserved'=>$this->db->now(), 'price'=>$aUpdate['amount'], 'commission'=>0, 'term'=>1, 'client_opinion'=>0, 'worker_opinion'=>0, 'client_expert_id'=>0, 'worker_expert_id'=>0]);
		#создаем документ счета 
		Fairplay::workflowsDocs($idwork)->makeSingle($userid, 2);
		$idinv = $this->db->one_data('SELECT id FROM bff_fairplay_workflows_docs WHERE workflow_id = :workflow_id AND document_id = 2', array(':workflow_id' => $idwork));
		$nameinv = $this->db->one_data('SELECT filename FROM bff_fairplay_workflows_docs WHERE id = :id', array(':id' => $idinv));
		#формируем массив данных для ссылки на документ счета
		$invdata = [];
		$invdata['id'] = $idinv;
		$invdata['workflow_id'] = $idwork;
		$invdata['user_id'] = $userid;
		$invdata['document_id'] = 2;
		$invdata['filename'] = $nameinv;
		#генерируем ссылку на документ счета
		$urlinv = Fairplay::url('document').$invdata['id'].'-'.md5($invdata['id'].$invdata['workflow_id'].$invdata['user_id'].$invdata['document_id'].$invdata['filename']);
		#создаем документ акта
		//Fairplay::workflowsDocs($idwork)->makeSingle($userid, 1);
		//$idakt = $this->db->one_data('SELECT id FROM bff_fairplay_workflows_docs WHERE workflow_id = :workflow_id AND document_id = 1', array(':workflow_id' => $idwork));
		//$nameakt = $this->db->one_data('SELECT filename FROM bff_fairplay_workflows_docs WHERE id = :id', array(':id' => $idakt));
		//формируем массив данных для ссылки на документ акта
		//$aktdata = [];
		//$aktdata['id'] = $idakt;
		//$aktdata['workflow_id'] = $idwork;
		//$aktdata['user_id'] = $userid;
		//$aktdata['document_id'] = 1;
		//$aktdata['filename'] = $nameakt;
		#генерируем ссылку на документ акта
		//$urlakt = Fairplay::url('document').$aktdata['id'].'-'.md5($aktdata['id'].$aktdata['workflow_id'].$aktdata['user_id'].$aktdata['document_id'].$aktdata['filename']);
		#сохраняем ссылки на документы в БД
		$this->db->update('bff_bills', ['invoice' => $urlinv], ['id'=>$schetid]);
		#удаляем воркфлоу
		$this->db->delete('bff_fairplay_workflows', ['id' => $idwork]);
	}
	else{
		# 3) если нет необработанных счетов, то делаем счет на списание
		$newbalance = $userbal - $amount;
		$billID = Bills::bills()->createBill_OutService(0, 0, $userid, $newbalance, $amount, $money, Bills::STATUS_WAITING, $sDescription = 'Вывод средств со счета в системе на банковский счет', $aSvcSettings = array());
		#создаем воркфлоу
		$idwork = $this->db->insert('bff_fairplay_workflows', ['order_id'=>0, 'client_id'=>16, 'worker_id'=>$userid, 'offer_id'=>0, 'fairplay'=>1, 'status'=>4, 'status_prev'=>6, 'arbitrage'=>0, 'created'=>$this->db->now(), 'modified'=>$this->db->now(), 'reserved'=>$this->db->now(), 'price'=>$amount, 'commission'=>0, 'term'=>1, 'client_opinion'=>0, 'worker_opinion'=>0, 'client_expert_id'=>0, 'worker_expert_id'=>0]);
		#создаем документ счета 
		Fairplay::workflowsDocs($idwork)->makeSingle($userid, 2);
		$idinv = $this->db->one_data('SELECT id FROM bff_fairplay_workflows_docs WHERE workflow_id = :workflow_id AND document_id = 2', array(':workflow_id' => $idwork));
		$nameinv = $this->db->one_data('SELECT filename FROM bff_fairplay_workflows_docs WHERE id = :id', array(':id' => $idinv));
		#формируем массив данных для ссылки на документ счета
		$invdata = [];
		$invdata['id'] = $idinv;
		$invdata['workflow_id'] = $idwork;
		$invdata['user_id'] = $userid;
		$invdata['document_id'] = 2;
		$invdata['filename'] = $nameinv;
		#генерируем ссылку на документ счета
		$urlinv = Fairplay::url('document').$invdata['id'].'-'.md5($invdata['id'].$invdata['workflow_id'].$invdata['user_id'].$invdata['document_id'].$invdata['filename']);
		#создаем документ акта
		//Fairplay::workflowsDocs($idwork)->makeSingle($userid, 1);
		//$idakt = $this->db->one_data('SELECT id FROM bff_fairplay_workflows_docs WHERE workflow_id = :workflow_id AND document_id = 1', array(':workflow_id' => $idwork));
		//$nameakt = $this->db->one_data('SELECT filename FROM bff_fairplay_workflows_docs WHERE id = :id', array(':id' => $idakt));
		#формируем массив данных для ссылки на документ акта
		//$aktdata = [];
		//$aktdata['id'] = $idakt;
		//$aktdata['workflow_id'] = $idwork;
		//$aktdata['user_id'] = $userid;
		//$aktdata['document_id'] = 1;
		//$aktdata['filename'] = $nameakt;
		#генерируем ссылку на документ акта
		//$urlakt = Fairplay::url('document').$aktdata['id'].'-'.md5($aktdata['id'].$aktdata['workflow_id'].$aktdata['user_id'].$aktdata['document_id'].$aktdata['filename']);
		#сохраняем ссылки на документы в БД
		$this->db->update('bff_bills', ['invoice' => $urlinv], ['id'=>$billID]);
		#удаляем воркфлоу
		$this->db->delete('bff_fairplay_workflows', ['id' => $idwork]);
	}
	# 4) снимаем деньги со счета пользователя
	Bills::bills()->updateUserBalance($userid, $money, false);
	$response = true;
	$this->ajaxResponse($response);
	}
	
	public function pushmobile()
	{
	    $userid = $this->input->post('user');
	    $onesignalid = $this->input->post('onesignalid');
	    $url = "https://onesignal.com/api/v1/players/".$onesignalid."?app_id=ИДЕНТИФИКАТОР_ПРИЛОЖЕНИЯ_В_УАНСИГНАЛ";
        $send = json_encode($sdata);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.54 Safari/537.36');
        curl_setopt($ch, CURLOPT_URL,$url);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $html = curl_exec($ch);
        curl_close($ch);
        $ans = json_decode($html, true);
        $uspushkey = $ans['identifier'];
        $uid = $this->db->one_data('SELECT id FROM bff_users_tokens_p0030a8 WHERE user_id = :uid AND token = :token', [':uid' => $userid, ':token'=>$uspushkey]);
        if($uid>0){
            
        }
        else{
            $this->db->insert('bff_users_tokens_p0030a8', ['user_id'=>$userid, 'created'=>$this->db->now(), 'token'=>$uspushkey, 'successed'=>'1', 'failed'=>'0']);
        }
	}
}
