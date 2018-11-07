<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_json extends CI_Controller {

	/**
   * @author : Ahmad Kurosaki
   * @web : 
   * @keterangan : Controller untuk penomoran
   **/
	
	public function CariNoJurnal()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$data['nojurnal'] = $this->app_model->MaxNoJurnal();
			$data['tgl'] = date('d-m-Y');
			echo json_encode($data);
			
		}else{
			header('location:'.base_url());
		}
	}
	
	public function CariNoAJP()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$data['nojurnal'] = $this->app_model->MaxNoAJP();
			$data['tgl'] = date('d-m-Y');
			echo json_encode($data);
			
		}else{
			header('location:'.base_url());
		}
	}
	
	public function CariNamaRek()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$no_rek = $this->input->post('no_rek');
			
			$text = "SELECT * FROM rekening WHERE no_rek='$no_rek'";
			$tabel = $this->app_model->manualQuery($text);
			$row = $tabel->num_rows();
			if($row>0){
				foreach($tabel->result() as $t){
					$data['nama_rek'] = $t->nama_rek;
					echo json_encode($data);
				}
			}else{
				$data['nama_rek'] ='';
				echo json_encode($data);
			}
		}else{
			header('location:'.base_url());
		}
	}
	
	public function CariNamaClient()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$no_po = $this->input->post('no_po');
			
			$sql = "SELECT a.no_po,a.tgl_po,a.tgl_pemasangan,a.id_client,b.nama_client FROM 
					po_intern a left join client b on a.id_client=b.id_client WHERE a.no_po='$no_po'";
			$tabel = $this->app_model->manualQuery($sql);
			$row = $tabel->num_rows();
			if($row>0){
				foreach($tabel->result() as $t){
					$data['id_client'] = $t->id_client;
					$data['nama_client'] = $t->nama_client;
					$data['tgl_po'] = $t->tgl_po;
					$data['tgl_pemasangan'] = $t->tgl_pemasangan;
					echo json_encode($data);
				}
			}else{
				$data['nama_client'] ='';
				echo json_encode($data);
			}
		}else{
			header('location:'.base_url());
		}
	}
	
}

/* End of file profil.php */
/* Location: ./application/controllers/profil.php */