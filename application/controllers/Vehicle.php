<?php
use Restserver \Libraries\REST_Controller ;
Class Vehicle extends REST_Controller{
 public function __construct(){
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
 header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
 parent::__construct();
 $this->load->model('vehicleModel');
 $this->load->library('form_validation');
 }
 public function index_get(){
 return $this->returnData($this->db->get('vehicles')->result(), false);
 }
 public function index_post($id = null){
 $validation = $this->form_validation;
 $rule = $this->vehicleModel->rules();
 if($id == null){
 array_push($rule,
 [

 ],
 [
 'field' => 'licensePlate',
 'label' => 'LicensePlate',
 'rules' => 'required|alpha_numeric|is_unique[vehicles.licensePlate]'
 ]
 );
 }
 else{
 array_push($rule,
 [
 ]
 );
 }
 $validation->set_rules($rule);
 if (!$validation->run()) {
 return $this->returnData($this->form_validation->error_array(), true);
 }
 $vehicle = new vehicleData();
 $vehicle->merk = $this->post('merk');
 $vehicle->type = $this->post('type');
 $vehicle->licensePlate = $this->post('licensePlate');
 $vehicle->created_at = $this->post('created_at');
 if($id == null){
 $response = $this->vehicleModel->store($vehicle);
 }else{
 $response = $this->vehicleModel->update($vehicle,$id);
 }
 return $this->returnData($response['msg'], $response['error']);
 }
 public function index_delete($id = null){
 if($id == null){
 return $this->returnData('Parameter Id Tidak Ditemukan', true);
 }
 $response = $this->vehicleModel->destroy($id);
 return $this->returnData($response['msg'], $response['error']);
 }
 public function returnData($msg,$error){
 $response['error']=$error;
 $response['message']=$msg;
 return $this->response($response);
 }
}
Class vehicleData{
 public $merk;
 public $type;
 public $licensePlate;
 public $created_at;
}