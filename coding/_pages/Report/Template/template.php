<?php

function style_css()
{
    include 'style_envelope.css';
}


function css_style()
{
    include 'style_default.css';
}

function heading($title='')
{
    include 'header.php';
}

function header_invoice($data = array())
{
    report::view('Template/header_invoice',$data);
}

function header_do($data = array())
{
    report::view('Template/header_do',$data);
}

// Address Quotation
function address_quotation_id($data = array())
{
    $title = isset($data['title']) ? $data['title'] : '';

    heading($title);
    report::view('Marketing/quotation/address',$data);
}

function address_quotation_en($data = array())
{
    $title = isset($data['title']) ? $data['title'] : '';

    heading($title);
    report::view('Marketing/quotation/address_en',$data);
}

// Address Invoice
function address_invoice($data = array())
{
   header_invoice($data);
   report::view('Marketing/invoice/address',$data);
}

// Address Delivery Order
function address_do($data = array())
{
   header_do($data);
   report::view('Marketing/delivery_order/address',$data);
}

// Address Inquiry
function address_inquiry($data = array())
{
    $title = isset($data['title']) ? $data['title'] : '';

    heading($title);
    report::view('Purchase/inquiry/address',$data);
}

// Address Purchase Order
function address_purchase($data = array())
{
    $title = isset($data['title']) ? $data['title'] : '';

    heading($title);
    report::view('Purchase/order/address',$data);
}

// Address Order Project
function address_order_project($data = array())
{
   heading($data);
   report::view('Purchase/project/address',$data);
}

// ----------------------------------------------------------
// QRcode Retail --------------------------------------------
// ----------------------------------------------------------

function footer_retail()
{
    include 'footer_retail_stunting.php';
}

// ----------------------------------------------------------
// Packing Slip ---------------------------------------------
// ----------------------------------------------------------

function header_packingslip()
{
    include 'header_packing.php';
}

// ----------------------------------------------------------
// Footer HTML ----------------------------------------------
// ----------------------------------------------------------

function sobad_footer($data = array())
{
    report::view('Template/footer',$data);
}

function footer_page()
{
    report::view('Template/footer_page');
}

function sobad_footer_secondary($data = array())
{
    report::view('Template/footer_secondary',$data);
}

function sobad_footer_2($data = array())
{
    report::view('Template/footer_2',$data);
}

function address2($data = array())
{
    $check = array_filter($data);
    if (empty($check)) {
        return '';
    }

    $sales = kmi_user::get_id($data['user'], array('name'));
    $sales = isset($sales[0]['name']) ? $sales[0]['name'] : '';

?>
    <table class="address_type1" style="width:100%;line-height:1px;">
        <tbody>
            <tr>
                <td colspan="2" style="font-size: 12px;"><span>To : </span></td>
            </tr>
            <tr>
                <td style="width:60%;vertical-align: top;">
                    <table>
                        <tbody>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;"><?php print($data['name_cont']); ?></td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;"><?php print($data['name_comp']); ?></td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="padding-right: 20px;font-size: 12px;"><?php print($data['_address_comp']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width:40%;vertical-align: top;">
                    <table>
                        <tbody>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">Invoice Date</td>
                                <td class="txt-content" style="font-size: 12px;">:</td>
                                <td class="txt-content" style="font-size: 12px;"><?php print(format_date_id($data['post_date'])); ?></td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">Invoice No.</td>
                                <td class="txt-content" style="font-size: 12px;">:</td>
                                <td class="txt-content" style="font-size: 12px;"><?php print($data['post_code']); ?></td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">Project No</td>
                                <td class="txt-content" style="font-size: 12px;">:</td>
                                <td class="txt-content" style="font-size: 12px;"><?php print($data['project_no']); ?></td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">Sales</td>
                                <td class="txt-content" style="font-size: 12px;">:</td>
                                <td class="txt-content" style="font-size: 12px;"><?php print($sales); ?></td>
                            </tr>
                            <tr>
                                <td>&nbsp;

                                </td>
                            </tr>
                            <tr>
                                <td class="txt-content" style="font-size: 12px;">Referensi</td>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">DO Number</td>
                                <td class="txt-content" style="font-size: 12px;">:</td><?php
                                                                                        foreach ($data['do_number'] as $key => $val) {
                                                                                            echo '<td class="txt_content" style="margin-left:25px"> ' . $val . '<br></td>';
                                                                                        }
                                                                                        ?>
                            </tr>
                            <tr>
                                <td class="sub_head3" style="font-size: 12px;">PO Number</td>
                                <td class="txt-content" style="font-size: 12px;">:</td>
                                <td class="txt-content" style="font-size: 12px;"><?php print($data['_po_number']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
<?php
}

function sobad_header($data = array())
{
    $title = $data['title'];
    $date = $data['date'];
?>
    <table class="sobad-table">
        <thead>
            <tr>
                <td rowspan="2" class="bold align-center" style="border: 2px solid;width:40%"><?php echo $title; ?></td>
                <td style="width: 20%;"></td>
                <td style="width: 15%;">Tanggal Faktur</td>
                <td style="width: 2%"> : </td>
                <td><?php echo $date; ?></td>
            </tr>
            <tr>
                <td colspan="1"></td>
                <td>Bill To</td>
                <td> : </td>
            </tr>
        </thead>
    </table>
<?php
}

function header_retail_default()
{
?>
    <table class="sobad-table">
        <thead>
            <tr>
                <td rowspan="2" class="bold align-center" style="border: 2px solid;width:40%"></td>
                <td rowspan="2" style="width: 20%;"></td>
                <td rowspan="2" style="width: 20%;">Tanggal Faktur</td>
                <td rowspan="2" style="width: 2%"> : </td>
                <td rowspan="2"></td>
            </tr>
        </thead>
    </table>
<?php
}