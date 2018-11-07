<div id="view">
    <!-- <div style="padding-bottom:5px;" align="center">
    Tahun : 
    <select name="th" id="th" class="kosong">
        <option value="">-PILIH-</option>
    </select>

    <select name="th" id="th" class="kosong">
        <option value="">-PILIH-</option>
    </select>

    <select name="th" id="th" class="kosong">
        <option value="">-PILIH-</option>
    </select>

    <select name="th" id="th" class="kosong">
        <option value="">-PILIH-</option>
    </select>
    <button type="button" name="cari" id="cari" class="easyui-linkbutton" data-options="iconCls:'icon-search'">Cari</button>
    <button type="button" name="cetak" id="cetak" class="easyui-linkbutton" data-options="iconCls:'icon-print'">Cetak</button>
    </div> -->
    <form action="<?php echo site_url('laporan/cetak_po_intern');?>" method="post">
        <table>
            <tr>
                <td>No. PO</td>
                <td>
                    <select name="no_po" id="no_po"  class="easyui-combobox">
                        <option value="">--Pilih--</option>
                        <?php 
                            foreach($po as $row){
                                echo "<option value='".$row->no_po."'>".$row->no_po."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Dibuat</td>
                <td>
                    <select name="dibuat" id="dibuat"  class="easyui-combobox">
                        <option value="">--Pilih--</option>
                        <?php 
                            foreach($user as $row){
                                echo "<option value='".$row->username."'>".$row->nama_lengkap."</option>";
                            }
                        ?>  
                    </select>
                </td>
            </tr>
            <tr>
                <td>Diperiksa</td>
                <td>
                    <select name="diperiksa" id="diperiksa"  class="easyui-combobox">
                        <option value="">--Pilih--</option>
                        <?php 
                            foreach($user as $row){
                                echo "<option value='".$row->username."'>".$row->nama_lengkap."</option>";
                            }
                        ?>  
                    </select>
                </td>
            </tr>
            <tr>
                <td>Disetujui</td>
                <td>
                    <select name="disetujui" id="disetujui"  class="easyui-combobox">
                        <option value="">--Pilih--</option>
                        <?php 
                            foreach($user as $row){
                                echo "<option value='".$row->username."'>".$row->nama_lengkap."</option>";
                            }
                        ?>  
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="export" value="pdf" id="pdf" class="easyui-linkbutton" data-options="iconCls:'icon-print'">CETAK DF</button>
                    <button type="submit" name="export" value="excel" id="excel" class="easyui-linkbutton" data-options="iconCls:'icon-print'">CETAK EXCEL</button>
                </td>
            </tr>
        </table>
    </form>

</div>