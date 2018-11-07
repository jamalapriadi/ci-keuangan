<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * @author : Ahmad Kurosaki
	 * @web :
	 * @keterangan : Controller untuk halaman profil
	 **/
	
	public function index()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$cari = $this->input->post('txt_cari');
			if(empty($cari)){
				$where = ' ';
			}else{
				$where = " WHERE no_po LIKE '%$cari%' OR nama_client LIKE '%$cari%'";
			}
			
			$d['prg']= $this->config->item('prg');
			$d['web_prg']= $this->config->item('web_prg');
			
			$d['nama_program']= $this->config->item('nama_program');
			$d['instansi']= $this->config->item('instansi');
			$d['usaha']= $this->config->item('usaha');
			$d['alamat_instansi1']= $this->config->item('alamat_instansi1');
			$d['alamat_instansi2']= $this->config->item('alamat_instansi2');

			
			$d['judul']="Order Penjualan";
			
			//paging
			$page=$this->uri->segment(3);
			$limit=$this->config->item('limit_data');
			if(!$page):
			$offset = 0;
			else:
			$offset = $page;
			endif;
			
			$text = "SELECT * FROM order_penjualan $where ";		
			$tot_hal = $this->app_model->manualQuery($text);		
			
			$d['tot_hal'] = $tot_hal->num_rows();
			
			$config['base_url'] = site_url() . '/admin/index/';
			$config['total_rows'] = $tot_hal->num_rows();
			$config['per_page'] = $limit;
			$config['uri_segment'] = 3;
			$config['next_link'] = 'Lanjut &raquo;';
			$config['prev_link'] = '&laquo; Kembali';
			$config['last_link'] = '<b>Terakhir &raquo; </b>';
			$config['first_link'] = '<b> &laquo; Pertama</b>';
			$this->pagination->initialize($config);
			$d["paginator"] =$this->pagination->create_links();
			$d['hal'] = $offset;
			

			$text = "SELECT * FROM order_penjualan $where 
					ORDER BY no_po DESC,tgl_po DESC 
					LIMIT $limit OFFSET $offset";
			$d['data'] = $this->app_model->manualQuery($text);
			
			//$text = "SELECT * FROM rekening ORDER BY no_rek ASC";
			//$d['list_rek'] = $this->app_model->manualQuery($text);
			
			
			$d['content'] = $this->load->view('admin/order_penjualan', $d, true);		
			$this->load->view('home',$d);
		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function edit()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			
			$id = $this->input->post('id');  
			$text = "SELECT * FROM order_penjualan WHERE no_po='$id' LIMIT 1";
			$data = $this->app_model->manualQuery($text);
			foreach($data->result() as $db){
				$d['no_po']				=$db->no_jurnal;
				$d['tgl_po']			=$this->app_model->tgl_str($db->tgl_po);
				$d['nama_client']		=$db->nama_client;
				$d['keterangan']		=$db->keterangan;
				$d['detail_pesanan']	=$db->detail_pesanan;
				$d['kode_rek']			=$db->kode_rek;
				$d['harga']				=$db->harga;
				$d['ppn']				=$db->ppn;
				$d['total']				=$db->total;
				echo json_encode($d);
			}

		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function hapus()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){			
			$id = $this->uri->segment(3);
			$this->app_model->manualQuery("DELETE FROM order_penjualan WHERE no_po='$id'");
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."index.php/admin'>";			
		}else{
			header('location:'.base_url());
		}
	}
	
	
	public function simpan()
	{
		
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
				$up['no_po']=$this->input->post('no_po');
				$up['tgl_po']=$this->app_model->tgl_sql($this->input->post('tgl_po'));
				$up['id_client']=$this->input->post('id_client');
				$up['keterangan']=$this->input->post('keterangan');
				$up['detail_pesanan']=$this->input->post('detail_pesanan');
				$up['kode_rek']=$this->input->post('kode_rek');
				$up['harga']=$this->input->post('harga');
				$up['ppn']=$this->input->post('ppn');
				$up['total']=$this->input->post('total');
				//$up['kode_rek']=str_replace(',','',$this->input->post('debet'));
				//$up['kredit']=str_replace(',','',$this->input->post('kredit'));
				$up['input_by']=$this->session->userdata('username');
				$up['last_update']=date('Y-m-d h:m:s');
				
				$id['no_po']=$this->input->post('no_po');
				$id['kode_rek']=$this->input->post('kode_rek');
				
				$no_po 	=$this->input->post('no_po');
				$kode_rek 	=$this->input->post('kode_rek');
				
				$text = "SELECT * FROM order_penjualan WHERE no_po='$no_po' AND kode_rek='$kode_rek'";
				$data = $this->app_model->manualQuery($text); //$this->app_model->getSelectedData("jurnal_umum",$id);
				if($data->num_rows()>0){
					$this->app_model->updateData("order_penjualan",$up,$id);
					echo 'Simpan data Sukses';
				}else{
					$this->app_model->insertData("order_penjualan",$up);
					echo 'Simpan data Sukses';		
				}
		}else{
				header('location:'.base_url());
		}
	
	}
	
	public function DetailJurnalUmum()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$id = $this->input->post('no_po'); 
			
			$text = "SELECT * FROM order_penjualan WHERE no_po='$id'";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$this->load->view('admin/detail_penjualan',$d);
		
			//echo $text;
		}else{
			header('location:'.base_url());
		}
	}
	
	public function hapusDetail()
	{
		$cek = $this->session->userdata('logged_in');
		if(!empty($cek)){
			$id = $this->input->post('no_po'); 
			$rek = $this->input->post('kode_rek'); 
			
			$text = "DELETE FROM order_penjualan WHERE no_po='$id' AND kode_rek='$rek'";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$text = "SELECT * FROM order_penjualan WHERE no_po='$id'";
			$d['data'] = $this->app_model->manualQuery($text);
			
			$this->load->view('admin/detail_penjualan',$d);

		}else{
			header('location:'.base_url());
		}
	}
	
}

/* End of file profil.php */
/* Location: ./application/controllers/profil.php */