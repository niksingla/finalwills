<?php
/*
*
* Template Name: View Will
*
*
*/

function validateWillsForm($willsFormData = []){
    $defaultValues = [
        'sec1' => null,
        'sec2' => [
            'prefix' => '',
            'suffix' => '',
            'firstName' => get_user_meta(get_current_user_id(),'first_name',true),
            'middleName' => '',
            'lastName' => get_user_meta(get_current_user_id(),'last_name',true),
            'email' => wp_get_current_user()->user_email,
            'gender' => '',
            'country' => '',
            'state' => '',
            'city' => ''
        ],
        'sec3' => [
            'status' => '',
            'children' => '',
            'numChildren' => 1,
            'childDetails' => [['name'=>'','relation'=>'','dob'=>'']],
            'grandChildren' => '',
            'numGrandChildren' => 1,
            'grandChildDetails' => [['name'=>'','relation'=>'','dob'=>'']],
            'fullName' => '',
            'relation' => '',
            'partnerGender' => '',
            'grandChildrenDirect' => 1,
            'deceased' => 1,   
            'deceasedDetails' => [['name'=>'','gender'=>'','relation'=>'']],
            'numDeceased' => 1,
        ],
        'sec4' => [
            'otherBeneficiaries' => 1,
            'numBeneficiaries' => 1,
            'beneficiaryDetails' => [['gender' => '', 'name' => '', 'relation' => '', 'address' => '','eqShare' => '']],
        ],
        'sec5' => [
            'guardianDetails' => [['childName'=>'','name'=>'','reason'=>'','alterName'=>'']]
        ],
        'sec6' => [
            'executorDetails' => [['name'=>'','relation'=>'','address'=>'']],
            'numExecutor' => 1,
            'alterOptions' => 1,
            'numAlterExecutor' => 0,
            'alterExecutorDetails' => [['name'=>'','relation'=>'','address'=>'']],
        ],
        'sec7' => [
            'charitableDonation' => 1,
            'numBequests' => 1,
            'bequestDetails' => [['type'=>1,'amount'=>'','percentage'=>'','asset'=>'','charityName'=>'']],
            'petTrust' => 1,
            'numPets' => 1,
            'petDetails' => [['name'=>'','type'=>'','amount'=>'','caretaker'=>'','alterCaretaker'=>'']],
            'possessionDist' => 1,
            'shareExp' => 1,
            'equalShare' => [['benefID'=>0,'share'=>'']],
            'numSpecifics' => 0,
            'specificsDetails' => [['type'=>1,'gift'=>'','description'=>'','giftBenefIndex'=>-1,'alterGiftBenefIndex'=>-1]],
            'everythingBenefIndex' => -1,
            'specificThingBenefIndex' => -1,
            'multiBenefProvisions' => [['radio'=>1,'alterBenefIndex'=>-1,'shareDesc'=>'']],
            'alterBenefProvisions' => ['radio'=>1,'everythingAlterBenefIndex'=>0,'restAllBenefIndex'=>0],
            'numAlterSpecifics' => 1,            
            'alterSpecificsDetails' => [['type'=>1,'gift'=>'','description'=>'','giftBenefIndex'=>-1,'alterGiftBenefIndex'=>-1]],
            'secondLevelAlter' => ['radio'=>1,'alterBenefIndex'=>0,'everythingDesc'=>''],
            'descSpecificBequest' => '',
            'residualAlterDetail' => ['residualDesc'=>'','residualBenefIndex'=>-1],
            'describeAlterDesc' => '',

        ],
        'sec8' => [
            'youngBenefs' => [['trust'=>1,'expiryAge'=>-1,'shareType'=>1,'fraction'=>'','ageGranted'=>-1,'fractionRemainder'=>'','atThisAge'=>-1]],
        ],
        'sec9' => ['forgive'=>'1','forgiveDetails'=>''],
        'sec10' => ['attachement'=>''],        
    ];
    $validatedData = [];
    foreach ($defaultValues as $section => $data) {
        if(is_array($defaultValues[$section])){            
            if(array_key_exists($section,$willsFormData)){
                $validatedData[$section] = is_array($data) ? array_merge($defaultValues[$section], $willsFormData[$section]) : $defaultValues[$section];
            }else{
                $validatedData[$section] = $defaultValues[$section];
            }
        }
    }   
   return $validatedData;
}
if (is_user_logged_in()) {
    $userData = get_userdata(get_current_user_id());
    $userMeta = get_user_meta(get_current_user_id());
    $fullName = sprintf("%s %s", $userMeta['first_name'][0], $userMeta['last_name'][0]);
    $will_data = [];
    if(array_key_exists('wills_form_data',$userMeta) && is_array($userMeta['wills_form_data'])){
        $will_data = unserialize($userMeta['wills_form_data'][0]);
        $will_data = validateWillsForm($will_data);        
    }else{
        $will_data = validateWillsForm($will_data);
    }
    // echo '<pre>';
    // print_r($will_data);
    // echo '</pre>';
    // exit;
}
$genderChild = [
    '1'=>'son',
    '2'=>'daughter',
    '3'=>'child',
    '4'=>'stepson',
    '5'=>'stepdaughter',
    '6'=>'stepchild',    
];
$genderGrandChild = [
    '1'=>'grandson',
    '2'=>'granddaughter',
    '3'=>'grandchild',
    '4'=>'grand stepson',
    '5'=>'grand stepdaughter',
    '6'=>'grand stepchild',    
];;
$name = $will_data['sec2']['prefix'] . ' ' . $will_data['sec2']['firstName'].' '.$will_data['sec2']['middleName'].' '.$will_data['sec2']['lastName'].' '.$will_data['sec2']['suffix'];
$address = $will_data['sec2']['city'].', '.$will_data['sec2']['state'].', '.$will_data['sec2']['country'];
$parish = $will_data['sec2']['city'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Will</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <style>
        body {
            font-family: Arial,Helvetica,sans-serif;
            margin: 0;
            padding: 0;
        }

        .pdf-content {
            width: 80%;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
        }

        /* #custom-data {
            margin-bottom: 20px;
            padding: 10px;            
        } */

        button {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        li{
            margin-bottom:10px;            
        }
        .main-list{
            list-style-type: none;
            counter-reset: my-counter;
            padding-left: 0;
            max-width: 980px;
        }
        .main-list > li{
            counter-increment: my-counter;
            margin-bottom: 10px;
            position: relative;
            padding-left: 60px;
        }
        .main-list > li::before {
            content: counter(my-counter) ". ";
            position: absolute;
            left: 20px;
        }        
        button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="pdf-content">
        <h1>Last Will And Testament</h1>
        <hr>
        <div>            
            <p>I, <?= $name ?></strong>, residing at <?= $address ?>, being of sound and disposing mind and memory, do hereby make and declare this to be my Last Will and Testament, revoking all prior Wills and Codicils.</p>
        </div>
        <div>
            <ol class="main-list">                
                <li>
                    <div>
                        <p>IDENTIFICATION OF FAMILY</p>
                        <p><strong>Marital Status</strong></p>
                        <ul style="list-style:none;text-indent:0;margin-bottom: 25px;">
                            <li>
                                <?php 
                                    switch ($will_data['sec3']['status']) {
                                        case '1':
                                            $text = 'I am single.';
                                            break;
                                        case '2':
                                            $text = 'I am married. My '. $will_data['sec3']['relation'].'\'s'.' name is '.$will_data['sec3']['fullName'].'.';
                                            break;
                                        case '3':
                                            $text = 'I am seperated. My '. $will_data['sec3']['relation'].'\'s'.' name is '.$will_data['sec3']['fullName'].'.';
                                            break;
                                        case '4':
                                            $text = 'I am seperated. My '. $will_data['sec3']['relation'].'\'s'.' name is '.$will_data['sec3']['fullName'].'.';
                                            break;
                                        case '5':
                                            $text = 'I am divorced. My '. $will_data['sec3']['relation'].'\'s'.' name is '.$will_data['sec3']['fullName'].'.';
                                            break;
                                        case '6':
                                            $text = 'I am widowed.';
                                            break;                                        
                                        default:                                            
                                            break;
                                    }
                                    echo "<p>$text</p>";
                                ?>
                                
                            </li>
                        </ul>
                        <p><strong>Living Descendants</strong></p>
                        <?php
                        
                            ?>
                            <ul style="list-style:none;text-indent:0;margin-bottom: 25px;">
                                <?php
                                if($will_data['sec3']['children']==2 && $will_data['sec3']['grandChildren']==2){
                                    $text = 'I do not have any living children or grandchildren.';
                                    echo "<p>$text</p>";
                                } else {
                                // 'childDetails' => [['name'=>'','relation'=>'','dob'=>'']],
                                for($i=0;$i<$will_data['sec3']['numChildren'];$i++){
                                    $rel = $genderChild[$will_data['sec3']['childDetails'][$i]['relation']];
                                    $formatted_date = date("F j, Y", strtotime($will_data['sec3']['childDetails'][$i]['dob']));
                                    $text = 'I have a '.$rel.', '.$will_data['sec3']['childDetails'][$i]['name'].' born '.$formatted_date;
                                    ?>
                                    <li><?= $text; ?></li>                                    
                                    <?php
                                }
                                for($i=0;$i<$will_data['sec3']['numGrandChildren'];$i++){
                                    $rel = $genderGrandChild[$will_data['sec3']['grandChildDetails'][$i]['relation']];
                                    $formatted_date = date("F j, Y", strtotime($will_data['sec3']['grandChildDetails'][$i]['dob']));
                                    $text = 'I have a '.$rel.', '.$will_data['sec3']['grandChildDetails'][$i]['name'].' born '.$formatted_date;
                                    ?>
                                    <li><?= $text; ?></li>                                    
                                    <?php
                                }}
                                ?>
                            </ul>
                            <?php
                        
                        ?>

                        <p><strong>Deceased Family Members</strong></p>
                        <?php
                       
                            ?>
                            <ul style="list-style:none;text-indent:0;margin-bottom: 25px;">
                                <?php
                                 if($will_data['sec3']['deceased']==1){
                                    $text = 'I do not have any deceased spouses or children.';
                                    echo "<p>$text</p>";
                                } else {
                                //    'deceasedDetails' => [['name'=>'','gender'=>'','relation'=>'']],
                                    for($i=0;$i<$will_data['sec3']['numDeceased'];$i++){
                                        $rel = $will_data['sec3']['deceasedDetails'][$i]['relation'];
                                        // $formatted_date = date("F j, Y", strtotime($will_data['sec3']['deceasedDetails'][$i]['dob']));
                                        $text = 'I have a deceased '.$rel.', '.$will_data['sec3']['deceasedDetails'][$i]['name'];
                                        ?>
                                        <li><?= $text; ?></li>                                    
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <?php
                        
                        ?>                        
                    </div>
                </li>
                <li>
                    <p>EXECUTORS</p>                    
                    <?php 
                    $text = '';
                    $numberExec = count($will_data['sec6']['executorDetails']);
                    for($i=0;$i<$numberExec;$i++){
                        $text .= $will_data['sec6']['executorDetails'][$i]['relation'].', '.$will_data['sec6']['executorDetails'][$i]['name'].', of '.$will_data['sec6']['executorDetails'][$i]['address'];                        
                        $text .= $i == $numberExec-2 ? ', and my': ($i == $numberExec-1 ? '': ', my ');
                        $text .= $numberExec-1 == $i ? '': '';
                    }
                    $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    $word = $formatter->format($numberExec);                    
                    $text2 = [$numberExec,$word];
                    $s = $numberExec > 1 ? 's': '';
                    
                    $text3 = '';
                    $numberAlterExec = count($will_data['sec6']['alterExecutorDetails']);                    
                    for($i=0;$i<$numberAlterExec;$i++){
                        $text3 .= $will_data['sec6']['alterExecutorDetails'][$i]['relation'].', '.$will_data['sec6']['alterExecutorDetails'][$i]['name'].', of '.$will_data['sec6']['alterExecutorDetails'][$i]['address'];                        
                        $text3 .= $i == $numberAlterExec-2 ? ', and then my ': ($i == $numberAlterExec-1 ? '': ', then my ');
                        $text3 .= $numberAlterExec-1 == $i ? '': '';
                    }

                    ?>
                    <?php if($will_data['sec6']['alterOptions']==1){ ?>
                        <p>I NOMINATE, CONSTITUTE and APPOINT my <?= $text; ?>, to be the Estate Trustee<?= $s ?>, Executor<?= $s ?>, and Trustee<?= $s ?> of this my Will. References to "my Executor" in my Will shall include each Executrix, Executor and Trustee of my Will, my estate, or portion thereof, who may be acting as such from time to time whether original or substituted and whether one or more. I request that no Executor, Trustee or successor in such capacity, or any other fiduciary hereunder, shall be required to furnish any sureties on his or her official bond in said capacity.</p>
                    <?php }?>
                    <?php if($will_data['sec6']['alterOptions']==2){ ?>
                        <p>I NOMINATE, CONSTITUTE and APPOINT my <?= $text; ?>, to be the Estate Trustee<?= $s ?>, Executor<?= $s ?>, and Trustee<?= $s ?> of this my Will, provided that there should be at all times <?= $text2[0]; ?> (<?= $text2[1]; ?>) Estate Trustee<?= $s ?>, Executor<?= $s ?> and Trustee<?= $s ?> of this my Will so that in the event that any one or more of my above-named Estate Trustee<?= $s ?>, Executor<?= $s ?> and Trustee<?= $s ?> shall have predeceased me or shall survive me but die before the trusts hereof shall have terminated or shall be unable or unwilling to act or to continue to act, I appoint, in the following order of priority, such one of the persons hereinafter named as shall not already be acting and as shall be able and willing to act to fill the vacancy so created, namely, my <?= $text3; ?>. References to "my Executor" in my Will shall include each Executrix, Executor and Trustee of my Will, my estate, or portion thereof, who may be acting as such from time to time whether original or substituted and whether one or more. I request that no Executor, Trustee or successor in such capacity, or any other fiduciary hereunder, shall be required to furnish any sureties on his or her official bond in said capacity.</p>
                    <?php }?>
                    <?php if($will_data['sec6']['alterOptions']==3){ ?>
                        <p>I NOMINATE, CONSTITUTE and APPOINT my <?= $text; ?>, to be the Estate Trustee<?= $s ?>, Executor<?= $s ?>, and Trustee<?= $s ?> of this my Will, provided that there should be at all times <?= $text2[0]; ?> (<?= $text2[1]; ?>) Estate Trustee<?= $s ?>, Executor<?= $s ?> and Trustee<?= $s ?> of this my Will so that in the event that any one or more of my above-named Estate Trustee<?= $s ?>, Executor<?= $s ?> and Trustee<?= $s ?> shall have predeceased me, or if either of them should refuse or be unable to act or continue to act as my Executor, then I appoint the survivor of my <?= $text; ?>, to be the sole Executor of this my Will. If my <?= $text; ?>, should both predecease me, or both should refuse or be unable to act or continue to act as my Executor(s), then I appoint my <?= $text3; ?>, to be the sole Executor of this my Will in the place of my <?= $text; ?>. References to "my Executor" in my Will shall include each Executrix, Executor and Trustee of my Will, my estate, or portion thereof, who may be acting as such from time to time whether original or substituted and whether one or more. I request that no Executor, Trustee or successor in such capacity, or any other fiduciary hereunder, shall be required to furnish any sureties on his or her official bond in said capacity.</p>
                    <?php }?>
                </li>
                <li>
                    <p>PAYMENT OF DEBTS</p>
                    <ol type="a">
                        <li>
                            <p>Except for liens and encumbrances placed on property as security for the repayment of a loan or debt, I direct that all debts and expenses owed by my estate to be paid out in the manner provided for by the laws of the State of California.</p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p>PAYMENT OF TAXES</p>
                    <ol type="a">
                        <li>
                            <p>I direct that all estate, inheritance, and other death taxes of any nature payable by reason of my death whether with respect to property passing under this Will or property not passing under this Will (except for any such taxes arising solely as a result of any power of appointment I may have at my death), shall be paid out in the manner provided for by the laws of the State of California.</p>
                        </li>
                    </ol>
                </li>
                <li>
                    <p>DISTRIBUTION OF PROPERTY AFTER DEBTS AND TAXES</p>
                    <ul type="none">
                        <li>
                            <p>Bequests to Charities</p>
                            <ol type="a">
                                <li>
                                    <p>To give the sum of 12 to Dummmm, for its general use and purposes.</p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <p>Trusts for Pets</p>
                            <ol type="a">
                                <li>
                                    <p>IN THE EVENT that my partner, dfsdf, predeceases me, I leave $23 in trust to my pet WEWE, named DAS, that shall be used for the health and care giving of my pet. The trustee (who shall be the beneficiary of this pet trust) I designate to act as caretaker shall be: adssa. If this caretaker is unable or unwilling to act for any reason, then I choose the following person to act as alternate trustee/caretaker: dscdf. The caretaker shall agree to take my pet into their home and provide my pet with proper care and attention at the same level that any reasonable pet owner would do for their pet. The funds provided in this pet trust are to be used for the benefit of my pet only. If my pet should die before the funds left in this trust are exhausted, then this pet trust shall terminate and the remaining balance shall be delivered to my other listed beneficiaries in my Will, as I have instructed.</p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <p>Residue</p>
                            <ol type="a">
                                <li>
                                    <p>In the event that my partner, dfsdf, survives me by a period of thirty (30) days, then pay, transfer, and assign the residue of my estate to her for her use absolutely.</p>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <p>Alternate Residue</p>
                            <ol type="a">
                                <li>
                                    <p>If my partner, dfsdf, predeceases me, or survives me but dies within a period of thirty (30) days of the date of my death, then:</p>
                                </li>
                            </ol>
                        </li>
                    </ul>                    
                </li>


                <!-- <li><strong>I DIRECT</strong> that as soon as possible after my decease my Trustees shall pay all my just debts, funeral, tombing and testamentary expenses.</li>
                <li>
                    <div>
                        <p><strong>FUNERAL AND BURIAL ARRANGEMENTS</strong></p>
                        <ul style="list-style:none;text-indent:0;margin-bottom: 25px;">
                            <p><strong>I HEREBY DIRECT that my body be prepared for burial in an appropriate manner and that my funeral expenses and any debts be paid out of my estate, along with the</strong> following:</p>
                            <ol type="a" style="text-indent: 10px;">
                                <li>That I be [specify any specific details that you would like to occur at your funeral]</li>
                                <li>That be clothed in [ please specify color and type]</li>
                                <li>That my remains be placed [ please specify how and where you would like your remains to be placed]</li>
                                <li>That the following songs be included in my funeral programme</li>
                                <li>
                                    <div>
                                        <p>
                                            That the following song is played at my wedding-
                                        </p>
                                        <ul type="none">
                                            <li>[please insert name of song1]</li>
                                            <li>[please insert name of song1]</li>
                                            <li>[please insert name of song1]</li>
                                        </ul>
                                    </div>
                                </li>
                            </ol>
                        </ul>
                    </div>
                </li>
                <li>
                    <div>
                        <p><strong>I GIVE DEVISE AND BEQUEATH:</strong></p>
                        <ol type="a">
                            <li>
                                <div>
                                    <p><strong>PROPERTY</strong></p>
                                    <ol type="i">
                                        <li>1st Property- situate at (please insert civic/ street address of the property), in the parish of [ INSERT PARISH]registered at (please insert Volume and Folio) of the Register Book of Titles to (please insert name of beneficiary).</li>
                                        <li>2nd Property- situate at (please insert civic/ street address of the property), in the parish of [ INSERT PARISH]registered at (please insert Volume and Folio) of the Register Book of Titles to (please insert name of beneficiary).</li>
                                        <li>3rd Property- situate at (please insert civic/ street address of the property), in the parish of [ INSERT PARISH]registered at (please insert Volume and Folio) of the Register Book of Titles to (please insert name of beneficiary).</li>
                                    </ol>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>SHARES AND STOCKS</strong></p>
                                    <ol type="i">
                                        <li>Shares in (please insert name of company) held in [INSERT COUNTRY] at [ INSERT NAME OF INVESTMENT COMPANY OR STOCK EXCHANGE] in account numbered (please insert account number) to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                        <li>Shares in (please insert name of company) held in [INSERT COUNTRY] at [ INSERT NAME OF INVESTMENT COMPANY OR STOCK EXCHANGE] in account numbered (please insert account number) to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                    </ol>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>INSURANCE</strong></p>
                                    <ol type="i">
                                        <li>Proceeds of insurance policy numbered (please insert account number), held at (please insert name of insurance company) located at (please insert address) , [INSERT COUNTRY]to (please insert name of beneficiary).</li>
                                        <li>Proceeds of insurance policy numbered (please insert account number), held at (please insert name of insurance company) located at (please insert address) , [INSERT COUNTRY]to (please insert name of beneficiary).</li>
                                    </ol>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>BANK ACCOUNTS</strong></p>
                                    <ol type="i">
                                        <li>Proceeds of bank account numbered [INSERT ACCOUNT NUMBER], held at [INSERT NAME OF FINANCIAL INSTITUTION] located at (please insert address) [INSERT COUNTRY] to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                        <li>Proceeds of bank account numbered [INSERT ACCOUNT NUMBER], held at [INSERT NAME OF FINANCIAL INSTITUTION] located at (please insert address) [INSERT COUNTRY] to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                        <li>Proceeds of bank account numbered [INSERT ACCOUNT NUMBER], held at [INSERT NAME OF FINANCIAL INSTITUTION] located at (please insert address) [INSERT COUNTRY] to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                    </ol>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>MOTOR VEHICLE</strong></p>
                                    <ol type="i">
                                        <li>[INSERT COLOR] [INSERT MAKE] [INSERT MODEL] Motor vehicle bearing Licence plate number [ INSERT NUMBER] and engine and chassis numbers (please insert numbers) to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                        <li>[INSERT COLOR] [INSERT MAKE] [INSERT MODEL] Motor vehicle bearing Licence plate number [ INSERT NUMBER] and engine and chassis numbers (please insert numbers) to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                        <li>[INSERT COLOR] [INSERT MAKE] [INSERT MODEL] Motor vehicle bearing Licence plate number [ INSERT NUMBER] and engine and chassis numbers (please insert numbers) to (please insert name of beneficiary) of [ INSERT ADDRESS].</li>
                                    </ol>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>UNPAID SALARY AND/EMOLUMENTS</strong></p>
                                    <p>Unpaid salary and/or emoluments with my employer, [Please insert Name of Employer] located at (please insert address) to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>NATIONAL HOUSING TRUST(NHT) CONTRIBUTIONS</strong></p>
                                    <p>Refund of National Housing Trust Contributions (please insert your National Insurance Scheme and Taxpayer Registration Numbers) to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>JEWELLERY</strong></p>
                                    <p>[ INSERT detail description] described as my Jewellery to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>FURNITURE</strong></p>
                                    <p>Furniture to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>PAINTINGS</strong></p>
                                    <p>Paintings to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <p><strong>FIREARM</strong></p>
                                    <p>Firearm bearing serial and firearm licence numbers (please insert numbers) to (please insert name of beneficiary) of (please insert address).</p>
                                </div>
                            </li>
                        </ol>
                    </div>
                </li>
                <li>
                    <div>
                        <p><strong>RESIDUAL ESTATE</strong></p>
                        <p>I give, devise and bequeath all the rest, residue and remainder of my estat, including any proceeds from the sale of assets to [(please insert name of residuary beneficiary) in equal shares.</p>                        
                    </div>
                </li> -->
            </ol>
            <div style="display:none;margin-top:40px;">
                <p><strong>IN WITNESS WHEREOF</strong> I have hereunto set my hand and seal this ...............day of .........20</p>
                <p>(Testator to sign here) .........................</p>
                <p><strong>SIGNED</strong> by the Testator the said (please insert name), a (please insert occupation) of (please insert address), in the parish of (please insert Parish), as my Last Will and Testament I delare that I have signed and executed this Last will and testament willingly and in the presence of the following witnesses, who are present at the same time and who have signed as witnesses in my presence:</p>
                <p><strong>WITNESSES</strong></p>
                <div>
                    <div style="display:inline-block;width:10%;padding:10px;border-right: 2px solid black;text-align: center;">
                        <p>Witnesses to sign here.</p>
                    </div>
                    <div style="display:inline-block;width:30%;padding:10px;">
                        <div>
                            <p>Name and Signature:  .........................</p>
                            <p>Address: .........................</p>
                            <p>Occupation:  .........................</p>
                            
                        </div>
                    </div>
                    <div style="display:inline-block;width:30%;padding:10px;">
                        <div>
                            <p>Name and Signature:  .........................</p>
                            <p>Address: .........................</p>
                            <p>Occupation:  .........................</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <button id="download-btn">Download PDF</button> -->
    </div>

    <!-- <script src="script.js"></script> -->
</body>
</html>
