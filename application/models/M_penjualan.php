<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_penjualan extends CI_Model
{
    /**
     * menampilkan data po
     */
    function tampilOrderPenjualan($no,$perpage,$cari){
        // select a.no_po, sum(b.total) as sum_total from po_intern a
        // left join order_penjualan b on b.no_po=a.no_po
        // group by a.no_po

        $this->db->select('no_po,tgl_po,tgl_po,tgl_pemasangan,nama_client,sum(total) as sum_total',FALSE);
        $this->db->from('order_penjualan');
        $this->db->join('client','client.id_client=order_penjualan.id_client','left');
        $this->db->order_by('no_po','desc');
        $this->db->order_by('tgl_po','desc');
        $this->db->group_by('no_po');

        if(!empty($cari)){
            $this->db->like('no_po',$cari);
            $this->db->or_like('nama_client',$cari);
            // $where = " WHERE no_po LIKE '%$cari%' OR nama_client LIKE '%$cari%'";
        }


        $this->db->limit($perpage,$no);
        $this->db->group_by("no_po");

        $query=$this->db->get();

        return $query->result_array();
    }

    function jumlahOrderPenjualan(){
        $this->db->select('*');
        $this->db->from('order_penjualan');
        $query=$this->db->get();
        $result=$query->num_rows();

        return $result;
    }

    /**
     * menampilkan list_po
     */
    function list_po(){
        // $sql = "SELECT a.no_po,a.tgl_po,a.tgl_pemasangan,
        // a.id_client,b.nama_client
        // FROM
		// po_intern a left join client b on a.id_client=b.id_client
        // where a.ket=1 ORDER BY a.no_po ASC";

        $this->db->from('po_intern as a');
        $this->db->where('a.ket',1);
        $this->db->join('client as b','b.id_client = a.id_client','left');
        $this->db->select('a.no_po,a.tgl_po,a.tgl_pemasangan,a.id_client,b.nama_client',false);
        $this->db->order_by('a.no_po','desc');

        $query=$this->db->get();

        return $query;
    }

    /**
     * Menampilkan po_interen berdasarkan id
     * table : po_intern
     */
    function no_po_by_id($id){
        $this->db->from('po_intern as a');
        $this->db->where('a.ket',1);
        $this->db->where('no_po',$id);
        $this->db->join('client as b','b.id_client = a.id_client','left');
        $this->db->select('a.no_po,a.tgl_po,a.tgl_pemasangan,a.id_client,b.nama_client',false);

        $query=$this->db->get();

        return $query;
    }

    function no_by_id($id){
        $this->db->from('po_intern as a');
        $this->db->join('client as b','b.id_client = a.id_client','left');
        $this->db->where('a.no_po',$id);

        $query=$this->db->get();

        return $query;
    }

    /**
     * Menampilkan list rekening
     */
    function list_rek(){
        // $text = "SELECT * FROM rekening ORDER BY no_rek ASC";
        $this->db->order_by('no_rek','asc');
        $this->db->select('*');
        $this->db->from('rekening');

        $query=$this->db->get();

        return $query;
    }

    /**
     *
     */
    function simpan_detail_penjualan($data){
        $this->db->insert('order_penjualan',$data);

        return true;
    }

    /**
     * update order penjualan by id
     */
    function update_order($order_id,$data){
        $this->db->where('id',$order_id);
        $this->db->update('order_penjualan',$data);

        return true;
    }

    /**
     * update po_intern
     * @param= no_po
     */
    function update_po_intern($id,$data){
        $this->db->where('no_po',$id);
		$this->db->update('po_intern',$data);
    }

    /**
     * hapus order penjualan by no_po
     * @param=no_po
     */
    function hapus_order_by_po($no_po){
        $this->db->where('no_po', $no_po);
        $this->db->delete('order_penjualan');
    }

    /**
     * hapus order penjualan by id
     */
    function hapus_order_penjualan_by_id($id){
        $this->db->where('id', $id);
        $this->db->delete('order_penjualan');

        return true;
    }

    /**
     * list order by no_po
     */
    function list_order_by_po($no_po){
        $this->db->select('order_penjualan.*,client.nama_client,rekening.nama_rek');
        $this->db->from('order_penjualan');
        $this->db->where('no_po',$no_po);
        $this->db->join('client','client.id_client=order_penjualan.id_client','left');
        $this->db->join('rekening','rekening.no_rek=order_penjualan.no_rek');
        $query=$this->db->get();

        return $query;
    }

    /**
     * detail order by id
     */

    function order_by_id($id){
        $this->db->where('id',$id);
        $this->db->from('order_penjualan');
        $this->db->join('client','client.id_client=order_penjualan.id_client','left');
        $this->db->select('order_penjualan.*,client.nama_client');

        $query=$this->db->get();

        return $query;
    }

    function get_code($id){
      $this->db->select('RIGHT(no_po,3) as kode', FALSE);
		  $this->db->order_by('no_po','DESC');
      $this->db->where('id_produk='.$id);
		  $this->db->limit(1);
		  $query = $this->db->get('po_intern');
		  if($query->num_rows() <> 0){
		   //jika kode ternyata sudah ada.
		   $data = $query->row();
		   $kode = intval($data->kode) + 1;
		  }
		  else {
		   //jika kode belum ada
		   $kode = 1;
		  }
		  $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
      $kodejadi = date('Y').".".$id.".".$kodemax;
		  return $kodejadi;
    }

    function get_all_po_intern(){
        $this->db->from('po_intern');

        $query=$this->db->get();

        return $query->result();
    }

    function get_all_user(){
        $this->db->from('users');
        $query=$this->db->get();

        return $query->result();
    }

    function po_intern_by_id($id){
        $this->db->from('po_intern');
        $this->db->where('no_po',$id);
        $this->db->join('client','client.id_client=po_intern.id_client','left');

        $query=$this->db->get();

        return $query;
    }
}
