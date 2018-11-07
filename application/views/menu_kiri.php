<?php
$level = $this->session->userdata('level');
?>
<div id="kiri" style="float:left;">
    <div id="Profil" class="easyui-panel" title="Level : <?php echo strtoupper($level);?>" style="float:left;width:170px;height:90px;padding:5px;">
    <?php
    $id = $this->session->userdata('username');
    $foto = $this->app_model->getFoto($id);
    if(empty($foto)){
    ?>
    <img style="float:left;padding:2px;" src="<?php echo base_url();?>asset/foto_profil/user.png" width="50" height="50" align="middle" />
    <?php }else{ ?>
    <img style="float:left;padding:2px;" src="<?php echo base_url();?>asset/foto_profil/<?php echo $foto;?>" width="50" height="50" align="middle" />
    <?php } ?>
    <p style="line-height:15px;">
    <b><?php echo $this->session->userdata('nama_lengkap');?></b><br />
    <a href="<?php echo site_url();?>/profile/edit/<?php echo $this->session->userdata('username');?>">Edit Profil</a>
    </p>
    </div>
    <div class="easyui-accordion" style="float:left;width:170px;">	
	<?php
    if($level =='super admin' OR $level =='admin'){
    ?>
	
	<div id="menu0" title="Master" data-options="iconCls:'icon-grafik'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/grafik">Master Barang</a></span>
            </li>
			<li>
                <span><a href="<?php echo site_url();?>/rekening">Nama Rekening</a></span>
            </li>
			<li>
                <span><a href="<?php echo site_url();?>/client">Client</a></span>
            </li>
			<li>
                <span><a href="<?php echo site_url();?>/users">User</a></span>
            </li>
        </ul>
    </div>
    </div>
	
	<div id="menu0" title="Marketing Support" data-options="iconCls:'icon-grafik'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/sample_form">Surat Penawaran</a></span>
            </li>
			<li>
                <span><a href="<?php echo site_url();?>/marketing">PO Intern</a></span>
            </li>
			
        </ul>
    </div>
    </div>
	
	<div id="menu0" title="Administrasi" data-options="iconCls:'icon-grafik'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/penjualan">Order Penjualan</a></span>
            </li>
			<li>
                <span><a href="<?php echo site_url();?>/marketing">Invoice Penjualan</a></span>
            </li>
			
        </ul>
    </div>
    </div>
	
    <div id="menu1" title="Menu" data-options="iconCls:'icon-tip'"  style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree" >
            <?php
            if($level =='super admin'){
            ?>
            <li data-options="iconCls:'icon-surat_perintah'">
                <a href="<?php echo site_url();?>/users">Users</a>
            </li>
            <?php } ?>
            <li data-options="iconCls:'icon-surat_perintah'">
                <a href="<?php echo site_url();?>/rekening">Rekening</a>
            </li>
            <li data-options="iconCls:'icon-surat_keputusan'">
                <a href="<?php echo site_url();?>/saldo_awal">Saldo Awal</a>
            </li>
            <li data-options="iconCls:'icon-surat_keluar'">
                <a href="<?php echo site_url();?>/jurnal_umum">Jurnal Umum</a>
            </li>
            <li data-options="iconCls:'icon-surat_keluar'">
                <a href="<?php echo site_url();?>/buku_besar">Buku Besar</a>
            </li>
            <li data-options="iconCls:'icon-surat_keluar'">
                <a href="<?php echo site_url();?>/jurnal_penyesuaian">Jurnal Penyesuaian</a>
            </li>
        </ul>
    </div>
    </div>
    <?php } ?>
    <div id="menu2" title="Laporan" data-options="iconCls:'icon-print'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/lap_buku_besar">Buku Besar</a></span>
            </li>
            <li>
                <span><a href="<?php echo site_url();?>/lap_neraca_saldo">Neraca Saldo</a></span>
            </li>
            <li>
                <span><a href="<?php echo site_url();?>/lap_neraca_lajur">Neraca Lajur</a></span>
            </li>
            <li>
                <span><a href="<?php echo site_url();?>/lap_laba_rugi">Laba Rugi</a></span>
            </li>
            <li>
                <span><a href="<?php echo site_url();?>/lap_neraca">Neraca</a></span>
            </li>
            <li>
                <span><a href="<?php echo site_url('laporan/po_intern');?>">PO Intern</a></span>
            </li>
        </ul>
    </div>
    </div>
    <div id="menu3" title="Grafik" data-options="iconCls:'icon-grafik'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/grafik">Jurnal</a></span>
            </li>
        </ul>
    </div>
    </div>
	<div id="menu3" title="Grafik" data-options="iconCls:'icon-grafik'" style="overflow:auto;padding:5px 0px;">
        <div title="TreeMenu" data-options="iconCls:'icon-search'" style="padding:0px;">
        <ul class="easyui-tree">
            <li>
                <span><a href="<?php echo site_url();?>/grafik">Jurnal</a></span>
            </li>
        </ul>
    </div>
    </div>
    </div>
</div>