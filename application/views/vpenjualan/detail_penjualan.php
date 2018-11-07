<script>
    $(function(){
        var id_client="";
        var listdetail=[];
        var aksi="tambah";
        var order_id="";

        $(':input:not([type="submit"])').each(function() {
            $(this).focus(function() {
                $(this).addClass('hilite');
            }).blur(function() {
                $(this).removeClass('hilite');});
        });	

        $(".angka").keypress(function(data){
            if (data.which!=8 && data.which!=0 && (data.which<48 || data.which>57)) {
                return false;
            }
        });

        $(document).on("click","#cekppn",function(){
            if($(this).is(':checked')){
                hitungtotal();
                // alert('dicek');
            }else{
                noppn();
                // alert('tidak dicek');
            }
        })

        $(document).on("change","#no_po",function(){
            var no_po=$("#no_po option:selected").val();

            $.ajax({
                url:"<?php echo site_url('penjualan/no_po_by_id');?>",
                data:"no_po="+no_po,
                type:"GET",
                beforeSend:function(){
                    kosongForm();
                },
                success:function(result){
                    data = JSON.parse(result);

                    id_client=data.id_client;
                    // $("#id_client").val(data.id_client);
                    $("#nama_client").val(data.nama_client);
                    $("#tgl_po").val(data.tgl_po);
                    $("#tgl_pemasangan").val(data.tgl_pemasangan);
                },
                errors:function(){
                    alert('failed to load data');
                }
            })
        })

        $(document).on("click","#simpan_1",function(){
            var no_po           = $("#no_po option:selected").val();
            var tgl_po			= $("#tgl_po").val();
            var tgl_pemasangan	= $("#tgl_pemasangan").val();
            var nama_client		= $("#nama_client").val();
            var detail_pesanan	= $("#detail_pesanan").val();
            var ukuran 			= $("#ukuran").val();
            var jumlah 			= $("#jumlah").val();
            var no_rek 			= $("#no_rek").val();
            var harga			= $("#harga").val();
            var ppn				= $("#ppn").val();
            var total			= $("#total").val();

            if(nama_client==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, No PO Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#no_po").focus();
			    return false;
            }else if(detail_pesanan==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, Detail Pesanan Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#detail_pesanan").focus();
			    return false;
            }else if(ukuran==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, Ukuran Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#ukuran").focus();
			    return false;
            }else if(jumlah==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, Jumlah Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#jumlah").focus();
			    return false;
            }else if(no_rek==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, No Rek Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#no_rek").focus();
			    return false;
            }else if(harga==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, Harga Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#harga").focus();
			    return false;
            }else if(total==""){
                $.messager.show({
                    title:'Info',
                    msg:'Maaf, Total Tidak Boleh Kosong',
                    timeout:2000,
                    showType:'slide'
                });
                
                $("#total").focus();
			    return false;
            }else{
                $.messager.confirm('Confirm','apakah anda yakin ingin menyimpan data ini?',function(r){
                    if (r){
                        var form=$('#formPenjualan').serializeArray();
                        form.push({ name: "id_client", value: id_client });
                        
                        // if(aksi=="tambah"){
                        //     var url="<?php echo site_url('penjualan/simpan')?>";
                        // }else{
                        //     form.push({ name: "order_id", value: order_id });
                        //     var url="<?php echo site_url('penjualan/update_order')?>";
                        // }

                        var url="<?php echo site_url('penjualan/simpan')?>";

                        $.ajax({
                            url:url,
                            type:"POST",
                            data:form,
                            beforeSend:function(){
                                
                            },
                            success:function(result){
                                data = JSON.parse(result);

                                if(data.success==true){
                                    kosongDetail();
                                    tampil_data_by_po(no_po);
                                }else{
                                    err=JSON.parse(data.errors);
                                    $.messager.show({
                                        title:'Info',
                                        msg:'Maaf, No PO Tidak Boleh Kosong',
                                        timeout:2000,
                                        showType:'slide'
                                    });
                                }
                            },
                            errors:function(){

                            }
                        })
                    }
                });
            }
        })

        $(document).on("click",".hapus",function(){
            var kode=$(this).attr("kode");
            
            $.messager.confirm('Confirm','Are you sure you want to delete record?',function(r){
                if (r){
                    $.ajax({
                        url: "<?php echo site_url('penjualan/hapus_po');?>",
                        type: "POST",
                        data:"no_po="+kode,
                        beforeSend:function(){
                        },
                        success: function (result) {
                            data = JSON.parse(result);

                            if(data.success==true){
                                location.reload();
                            }else{
                                alert('data gagal dihapus');
                            }
                        }
                    });
                }
            });
        })

        function addKoma(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function tampil_data_by_po(id){
            var el="";
            $.ajax({
                url:"<?php echo site_url('penjualan/order_by_po');?>",
                type:"GET",
                data:"no_po="+id,
                beforeSend:function(){
                    $("#tampil_data_by_po").empty().html('Please Wait...');
                },
                success:function(result){
                    data = JSON.parse(result);
                    el+='<table id="dataTable" width="100%">'+
                        '<tr>'+
                            '<th>No</th>'+
                            '<th>Detail Pesanan</th>'+
                            '<th>Ukuran</th>'+
                            '<th>Jumlah</th>'+
                            '<th>Kode Rek</th>'+
                            '<th>Harga</th>'+
                            '<th>PPN</th>'+
                            '<th>Total</th>'+
                            '<th>Hapus</th>'+
                        '</tr>';
                    
                    var no=0;
                    if(data.length>0){
                        var totalharga=parseInt(0);
                        var totalppn=parseInt(0);
                        var totalall=parseInt(0);

                        $.each(data,function(a,b){
                            no++;
                            var icon="<?php echo base_url();?>asset/images/del.png";

                            el+="<tr>"+
                                "<td>"+no+"</td>"+
                                "<td>"+b.detail_pesanan+"</td>"+
                                "<td>"+b.ukuran+"</td>"+
                                "<td>"+b.jumlah+"</td>"+
                                "<td>"+b.no_rek+" - "+b.nama_rek+"</td>"+
                                "<td>"+addKoma(b.harga)+"</td>"+
                                "<td>"+addKoma(b.ppn)+"</td>"+
                                "<td>"+addKoma(b.total)+"</td>"+
                                "<td>"+
                                    "<a href='#' class='hapusdetail' kode='"+b.id+"' nopo='"+b.no_po+"'>"+
                                        "<img src='"+icon+"' title='Hapus'>"+
                                    "</a>"+
                                "</td>"+
                            "</tr>";

                            totalharga=parseInt(totalharga)+parseInt(b.harga);
                            totalppn=parseInt(totalppn)+parseInt(b.ppn);
                            totalall=parseInt(totalall)+parseInt(b.total);
                        })

                        el+="<tr class='stripe1'>"+
                            "<td colspan='5' align='center'><b>Total</b></td>"+
                            "<td>"+addKoma(totalharga)+"</td>"+
                            "<td>"+addKoma(totalppn)+"</td>"+
                            "<td>"+addKoma(totalall)+"</td>"+
                            "<td></td>"+
                        "</tr>";
                    }else{
                        el+="<tr>"+
                            "<td colspan='9'>Tidak ada data</td>"+
                        "</tr>";
                    }

                    $("#tampil_data_by_po").empty().html(el);
                },
                errors:function(){
                    
                }
            })
        }

        $(document).on("click",".hapusdetail",function(){
            var kode=$(this).attr("kode");
            var nopo=$(this).attr("nopo");
            
            $.messager.confirm('Confirm','Are you sure you want to delete record?',function(r){
                if (r){
                    $.ajax({
                        url: "<?php echo site_url('penjualan/hapus_order_penjualan_by_id');?>",
                        type: "POST",
                        data:"id="+kode,
                        beforeSend:function(){
                        },
                        success: function (result) {
                            data = JSON.parse(result);

                            if(data.success==true){
                                tampil_data_by_po(nopo);
                            }else{
                                alert('data gagal dihapus');
                            }
                        }
                    });
                }
            });
        })

        $(document).on("click",".edit",function(){
            aksi="edit";
            order_id=$(this).attr("orderid");
            var kode=$(this).attr("kode");

            // tampil_data_by_po(kode);
            
            $.ajax({
                url:"<?php echo site_url('penjualan/no_by_id');?>",
                type:"GET",
                data:"no_po="+kode,
                beforeSend:function(){

                },
                success:function(result){
                    data = JSON.parse(result);
                    $('#no_po')
                        .append($("<option></option>")
                                    .attr("value",data.no_po)
                                    .text(data.no_po)); 

                    $("#nama_client").val(data.nama_client);
                    $("#tgl_po").val(data.tgl_po);
                    $("#tgl_pemasangan").val(data.tgl_pemasangan);
                    $("#no_po").val(data.no_po);
                    id_client=data.id_client;

                    tampil_data_by_po(kode);
                },
                errors:function(){
                    
                }
            })
            $("#view").hide();
            $("#form").show();
        })

        function kosongForm(){
            $("#id_client").val('');
            $("#nama_client").val('');
            $("#tgl_po").val('');
            $("#tgl_pemasangan").val('');
        }

        function kosongDetail(){
            $("#detail_pesanan").val('');
            $("#ukuran").val('');
            $("#jumlah").val('');
            $("#no_rek").val('');
            $("#harga").val(0);
            $("#ppn").val(0);
            $("#total").val(0);
            $("#cekppn").prop('checked', false);
        }

        function showListPo(){
            
        }

        $(document).on("click","#tambah",function(){
            aksi="tambah";
            $("#view").hide();
            $("#form").show();
        })

        $(document).on("click","#kembali",function(){
            window.location.assign('<?php echo base_url();?>index.php/penjualan');
        })

        $(document).on("keyup","#harga",function(){
            // hitungtotal();
            if($("#cekppn").is(':checked')){
                hitungtotal();
                // alert('dicek');
            }else{
                noppn();
                // alert('tidak dicek');
            }
        })

        $(document).on("keyup","#ppn",function(){
            hitungtotal();
        })

        function hitungtotal(){
            var harga=parseInt($("#harga").val());
            // var ppn=parseInt(10);

            var tambahan=harga * (10 / 100);
            var total=harga+tambahan;

            $("#total").val(total);
            $("#ppn").val(tambahan);
        }

        function noppn(){
            var harga=parseInt($("#harga").val());
            // var ppn=parseInt(10);

            var tambahan=harga;
            var total=harga;

            $("#total").val(total);
            $("#ppn").val(0);
        }
        
    })
</script>

<!-- view depan -->
<div id="view">
    <div style="float:left; padding-bottom:5px;">
        <button type="button" name="tambah" id="tambah" class="easyui-linkbutton" data-options="iconCls:'icon-add'">Tambah Data</button>

        <a href="<?php echo site_url();?>/penjualan">
            <button type="button" name="refresh" id="refresh" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">Refresh</button>
        </a>
    </div>

    <div style="float:right; padding-bottom:5px;">
        <form name="form" method="post" action="<?php echo site_url('penjualan');?>">
            Cari No.Jurnal/No.Rek : <input type="text" name="txt_cari" id="txt_cari" size="50" />
            <button type="submit" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
        </form>
    </div>

    <div style="padding:10px;"></div>

    <table id="dataTable" width="100%">
        <tr>
            <th>No</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Tanggal Pemasangan</th>
            <th>Nama Client</th>
            <th>Total Nilai</th>
            <th>Opsi</th>
        </tr>
        <?php 
            $no=0;
            if(count($data)>0){
                $total=0;
                foreach($data as $row){
                    $no++;
                    echo "<tr>
                            <td>".$no."</td>
                            <td>".$row['no_po']."</td>
                            <td>".date('d-M-Y',strtotime($row['tgl_po']))."</td>
                            <td>".date('d-M-Y',strtotime($row['tgl_pemasangan']))."</td>
                            <td>".$row['nama_client']."</td>
                            <td>".number_format($row['sum_total'])."</td>
                            <td align='center' width='80'>
                                <a href='#' class='edit' kode='".$row['no_po']."'>
                                    <img src='".base_url()."asset/images/ed.png' title='Edit'>
                                </a>
                                <a href='#' class='hapus' kode='".$row['no_po']."'>
                                    <img src='".base_url()."asset/images/del.png' title='Hapus'>
                                </a>
                            </td>
                        </tr>";

                    $total+=$row['sum_total'];
                }
                echo '<tr class="stripe1">
                    <td colspan="5" align="center"><b>Total</b></td>
                    <td>'.number_format($total).'</td>
                    <td></td>
                </tr>';

            }else{
                echo '<tr>
                    <td colspan="7" align="center" >Tidak Ada Data</td>
                </tr>';
            }
        ?>
    </table>

    <!-- Paginate -->
    <?php echo "<table align='center'><tr><td>".$pagination."</td></tr></table>"; ?>

</div>
<!-- end view depan-->


<!-- view form -->
<div id="form" style="display:none;">
    <form onsubmit="return false;" id="formPenjualan">
        <div id="pesan"></div>

        <fieldset class="atas">
            <table width="100%">
                <tr>
                    <td width="50%" valign="top">
                        <table width="100%">
                            <tr>
                                <td width="20%">No PO</td>
                                <td width="5">:</td>
                                <td>
                                    <!-- <input type="" name="" id="no_po2"> -->
                                    <select name="no_po" id="no_po" class="kosong">
                                        <option value="" disabled selected>--PILIH--</option>
                                        <?php 
                                            foreach($list_po->result_array() as $row){
                                                echo "<option value='".$row['no_po']."'>".$row['no_po']."</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="id_client" id="id_client" class="kosong" size="20" maxlength="12" readonly="readonly" hidden="hidden" />
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Nama Client</td>
                                <td width="5">:</td>
                                <td>
                                    <input type="text" name="nama_client" id="nama_client" class="kosong" size="50" maxlength="50" readonly="readonly" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <table width="100%">
                            <tr>
                                <td width="20%">Tanggal PO</td>
                                <td width="5">:</td>
                                <td><input type="text" class="tanggal" name="tgl_po" id="tgl_po" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
                            </tr>
                            <tr>    
                                <td width="20%">Tanggal Pemasangan</td>
                                <td width="5">:</td>
                                <td><input type="text" class="tanggal" name="tgl_pemasangan" id="tgl_pemasangan" class="kosong" size="30" maxlength="30" readonly="readonly" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>  
        </fieldset>

        <div style="margin:5px;"></div>

        <fieldset class="atas">
            <table width="100%">
                <tr>
                    <td width="50%" valign="top">
                        <table width="100%">
                            <tr>
                                <td width="20%">Detail Pesanan</td>
                                <td width="5">:</td>
                                <td>
                                    <textarea name="detail_pesanan" id="detail_pesanan" style="width:300px; height:50px;"></textarea>
                                    <div id="error_detail_pesanan"></div>
                                </td>
                            </tr>
                            <tr>    
                                <td>Ukuran</td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="ukuran" id="ukuran" size="20" maxlength="20" />
                                </td>            
                            </tr>
                            <tr>
                                <td width="20%">Jumlah</td>
                                <td width="5">:</td>
                                <td>
                                    <input type="text" name="jumlah" id="jumlah" size="20" maxlength="20" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    
                    <td width="50%" valign="top">
                        <table width="100%">
                            <tr>
                                <td>Kode Rekening</td>
                                <td>:</td>
                                <td>
                                    <select name="no_rek" id="no_rek" class="kosong">
                                        <option value="" disabled selected>--PILIH--</option>
                                        <?php 
                                            foreach($list_rek->result_array() as $row){
                                                echo "<option value='".$row['no_rek']."'>".$row['no_rek']." | ".$row['nama_rek']."</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                                
                            </tr>
                            <tr>    
                                <td>Harga</td>
                                <td>:</td>
                                <td>
                                    <input class="angka" value="0" type="text" name="harga" id="harga" size="20" maxlength="20" />
                                </td>
                            </tr>
                            <tr>    
                                <td>PPN</td>
                                <td>:</td>
                                <td>
                                    <input type="checkbox" id="cekppn">
                                    <input type="hidden" value="0" class="angka" type="text" name="ppn" id="ppn" size="20" maxlength="2" min="0" max="100"/>
                                </td>
                            </tr>
                            <tr>    
                                <td>Total</td>
                                <td>:</td>
                                <td>
                                    <input class="angka" value="0" type="text" name="total" id="total" size="20" maxlength="20" readonly />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>


    <fieldset class="bawah">
        <table width="100%">
            <tr>
                <td colspan="3" align="center">
                <button name="simpan_1" id="simpan_1" class="easyui-linkbutton" data-options="iconCls:'icon-save'">SIMPAN</button>
                <!-- <button name="tambah_data" id="tambah_data" class="easyui-linkbutton" data-options="iconCls:'icon-add'">TAMBAH</button> -->
                <button type="button" name="kembali" id="kembali" class="easyui-linkbutton" data-options="iconCls:'icon-close'">TUTUP</button>
                </td>
            </tr>
        </table>  
    </fieldset>

    <div id="tampil_data_by_po"></div>
</div>
<!-- end view form -->