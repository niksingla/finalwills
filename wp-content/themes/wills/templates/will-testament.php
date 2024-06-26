<?php
/* Template Name: Will Testament Service  */

//Terminate if the Wills Helper plugin is not activated
// if (!is_plugin_active('wills-helper/wills-helper.php')) {
//     echo 'Wills Helper Plugin is not active, activate it first.';
//     exit;
// }

add_action('wp_head', function () {
    $html = <<<'HTML'
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
        <script>
                tailwind.config = {
                important: true,
            }
        </script>
    HTML;
    $script = '<script src="'.get_stylesheet_directory_uri().'/assets/js/countries.js"></script>';
    echo $script;
    echo $html;
});

get_header();
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
?>
    <style>
            /* [x-cloak] {
                display: none !important;
            } */

        .ast-container {
            margin: 0;
            padding: 0;
            width: 100%;
            max-width:100%!important;
        }
    </style>
    <!-- Code here -->
    <script>
        const willsFormData = <?php echo json_encode($will_data); ?>;
        // console.log(willsFormData);        
    </script>
    <div x-data="data" x-init="updateState();" class="w-full">
        <div x-cloak>
            <div class="w-11/12 mx-auto p-8">
                <a href="<?= site_url() ?>/online-estate-planning/">
                    <button class="bg-green-600 text-white rounded border border-green-600 px-4 py-2 hover:bg-green-500">Back</button>
                </a>

                <div class="border-b-2 border-red-700 py-4">
                    <h1 class="text-4xl text-blue-500">MyWill™ - Main Menu For <?= $fullName ?></h1>
                </div>

            </div>
            <div x-show="activeForm === 'home'">
                <div class="w-10/12 mx-auto p-10">

                    <div class="mx-auto">

                        <div class="my-8">
                            <p>Here you can create a legal Last Will and Testament, custom-made for your local jurisdiction. You must print, sign and witness your Last Will and Testament to make it a legal document.</p>
                        </div>

                        <div class="border-2 border-blue-400 px-8 py-4 w-9/12 rounded-lg mb-8">
                            <ul>
                                <li @click="openPage('createMod')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-file"></i> </span>Create or Modify your Will</li>
                                <li @click="openPage('view')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-eye"></i> </span>View your Will</li>
                                <li @click="openPage('delete')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-trash-can"></i> </span>Delete your Will</li>
                            </ul>
                        </div>


                        <div class="border-2 border-blue-400 px-8 py-4 w-9/12 rounded-lg mb-8">
                            <ul>
                                <li @click="openPage('downPdf')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-file"></i> </span>Download your Will (PDF file) [requires PDF viewer (e.g. Adobe Reader)]</li>
                                <li @click="openPage('email')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-eye"></i> </span>Have your Will sent to you by Email (PDF file)</li>
                                <li @click="openPage('downWord')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-trash-can"></i> </span>Download your Will (Word document) [requires Microsoft Word]</li>
                            </ul>
                        </div>

                        <div class="border-2 border-blue-400 px-8 py-4 w-9/12 rounded-lg mb-8">
                            <ul>
                                <li @click="openPage('instructions')" class="text-xl font-bold my-4 text-blue-400 hover:text-blue-500 cursor-pointer"><span class="border-2 text-lg rounded-full p-4 inline-block mr-2 border-blue-400 text-center"><i class="fa-regular fa-file"></i> </span>Instructions for Printing, Signing and Updating your Will</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="activeForm === 'createMod'">
                <div class="w-10/12 mx-auto p-10">
                    <p>
                        Below is a list of the sections in the MyWill™ question-and-answer wizard.</p>
                    <br>

                    <p>If you have not yet started to answer the questions, or you'd like to review and possibly make changes to your answers, or even if you're not sure, click on "Start Here". Otherwise, you may click on any section to continue from that point or to make modifications by jumping to a particular section.
                    </p>

                    <p class="font-bold text-blue-400 hover:text-blue-500 my-8"><a href="">Return to the MyWill™ main menu</a></p>

                    <div>
                        <ul>

                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec1')">
                                Section 1: Introduction <span class="text-red-600">START HERE</span>
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec2')">
                                Section 2: Personal Details
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec3')">
                                Section 3: Family Status
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec4')">
                                Section 4: Other Beneficiaries
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec5')">
                                Section 5: Guardians for Minor Children
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec6')">
                                Section 6: Executor
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec7')">
                                Section 7: Distribute Your Possessions
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec8')">
                                Section 8: Trusts for Young Beneficiaries
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec9')">
                                Section 9: Forgive Debts
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec10')">
                                Section 10: Next Steps
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div x-show="mainForm" class="w-10/12 mx-auto p-10">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row justify-center gap-4">
                        <div class="text-xl">Progress</div>
                        <div class="flex flex-col">
                            <progress x-bind:value="progressValue*10" max="100"></progress>
                            <div>Section <span x-text="progressValue"></span> of 10</div>
                        </div>
                    </div>
                    <div>
                        <select id="sec-select" x-model="selectedOpt" @change="selectChanged($event)">
                            <template x-for="(item, index) in sectionSelOption">
                                <option :value="allPages[index]" :selected="selectedOpt == allPages[index]" x-text="item"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="mt-10">
                    <div class="w-full">
                        <div class="flex flex-row justify-start gap-12">
                            <!-- Q n A -->
                            <div class="w-4/12 bg-blue-600 text-white">
                                <div class="flex-initial px-10 py-4">
                                    <p class="text-2xl">Common Questions:</p>
                                    <div class="mt-8">
                                        <template x-for="[question, answer] in Object.entries(qna[activeForm])">
                                            <div>
                                                <details>
                                                    <summary x-text="question"></summary>
                                                    <p class="text-sm" x-text="answer"></p>
                                                </details>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="w-8/12">
                                <div x-show="activeForm === 'sec1'">
                                    <h1 class="text-4xl text-blue-500">Introduction</h1>
                                    <i class="font-bold">We've made this easy! This should only take a short amount of your time...</i>
                                    <p>You will be asked a series of questions to help you create your Last Will and Testament.</p>
    
                                    <p>While answering the questions, if you need general assistance on the section, just read the Common Questions which appear on every page. If you don't see the questions, simply click on the big near the top of the page.</p>
    
                                    <p>Specific help for parts of a page that may be unclear is available by tapping (or moving your mouse over) the small symbol which appears next to some questions.</p>
    
                                    <p>At any point you can save your work and return later.</p>
    
                                    <p>When you are done, you should print and sign your document in the presence of witnesses to make it a legal Will.</p>
    
                                    <p>To begin stepping through these questions, click on the "NEXT" button which appears below...</p>
                                </div>
                                <div x-show="activeForm === 'sec2'">
                                    <h1 class="text-4xl text-blue-500">Personal Details</h1>
                                    <p>
                                        It is important that you provide the information below so that the MyWill™ wizard can format a document that is custom-made based on your name, gender and local jurisdiction.
                                    </p>
    
                                    <p><span class="text-red-500">*</span> = required information</p>
                                    <div>
                                        <div>
                                            <p></p>
                                            <div>
    
                                            </div>
                                            <div>
                                                <div>
                                                    <label for="prefix">Prefix (eg. Mr., Ms., Dr.)<span class="text-red-500">*</span></label>
                                                    <input x-model="formData.sec2.prefix" type="text" id="prefix" value="" @input="validate('prefix')" :class="{'border-red-500': validateError.prefix}">
                                                    <small x-text="validateError.prefix" class="text-red-500 block"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xl-4">
                                                    <div>
                                                        <label for="firstName">First Name<span class="text-red-500">*</span></label>
                                                        <input x-model="formData.sec2.firstName" type="text" value="" id="firstName" @input="validate('firstName')" :class="{'border-red-500': validateError.firstName}">
                                                        <small x-text="validateError.firstName" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xl-4">
                                                    <div>
                                                        <label for="middleName">Middle Name</label>
                                                        <input x-model="formData.sec2.middleName" type="text" value="" id="middleName" @input="validate('middleName')" :class="{'border-red-500': validateError.middleName}">
                                                        <small x-text="validateError.middleName" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xl-4">
                                                    <div>
                                                        <label for="lastName">Last Name/Surname<span class="text-red-500">*</span></label>
                                                        <input x-model="formData.sec2.lastName" type="text" value="" id="lastName" @input="validate('lastName')" :class="{'border-red-500': validateError.lastName}">
                                                        <small x-text="validateError.lastName" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xl-4">
                                                    <div>
                                                        <label for="suffix">Suffix (eg. Jr., Sr.)</label>
                                                        <input x-model="formData.sec2.suffix" type="text" value="" id="suffix" @input="validate('suffix')" :class="{'border-red-500': validateError.suffix}">
                                                        <small x-text="validateError.suffix" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div>
                                                        <label for="country">Country<span class="text-red-500">*</span></label>
                                                        <select x-model="formData.sec2.country" id="country" class="select" @change="validate('country')" :class="{'border-red-500': validateError.country}"></select>
                                                        <small x-text="validateError.country" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div>
                                                        <label for="state">State / Province / County <span class="text-red-500">*</span></label>
                                                        <select x-model="formData.sec2.state" id="state" class="select" @change="validate('state')" :class="{'border-red-500': validateError.state}"></select>
                                                        <input type="hidden" id="prePopulatedState" x-model="formData.sec2.state">
                                                        <small x-text="validateError.state" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                                <!-- Populate the Countires and States using assets/js/countries.js -->
                                                <script type="text/javascript">
                                                    populateCountries("country", "state");
                                                </script>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <label for="city">City / Town<span class="text-red-500">*</span></label>
                                                    <input x-model="formData.sec2.city" type="text" value="" id="city" @input="validate('city')" :class="{'border-red-500': validateError.city}">
                                                    <small x-text="validateError.city" class="text-red-500 block"></small>
    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <label for="email">Email Address<span class="text-red-500">*</span></label>
                                                    <input x-model="formData.sec2.email" type="email" value="" id="email" @input="validate('email')" :class="{'border-red-500': validateError.email}">
                                                    <small x-text="validateError.email" class="text-red-500 block"></small>
    
                                                </div>
                                            </div>
                                            <div class="col-12 mt-4">
                                                <div>
                                                    <p class="form-label mt-2">Gender pronoun:<span class="text-red-500">*</span></p>
                                                    <div class="flex flex-col">
                                                        <div>
                                                            <input x-model="formData.sec2.gender" type="radio" name="gender" value="1" class="mr-4" @change="validate('gender')"><label for="">Male (he/his)</label>
                                                        </div>
                                                        <div>
                                                            <input x-model="formData.sec2.gender" type="radio" name="gender" value="2" class="mr-4" @change="validate('gender')"><label for="">Female (she/her)</label>
                                                        </div>
                                                        <div>
                                                            <input x-model="formData.sec2.gender" type="radio" name="gender" value="3" class="mr-4" @change="validate('gender')"><label for="">Neutral (they/their)</label>
                                                        </div>
                                                        <small x-text="validateError.gender" class="text-red-500 block"></small>
    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec3'">

                                    <!-- Section 3 first form - Intro  -->
                                    <div x-show="activeSubForm === 'intro'">
                                        <h1 class="text-4xl text-blue-500">Family Status</h1>
                                        <p>* = required information</p>
                                        <div>
                                            <label for="status">Marital Status</label>
                                            <select x-model="formData.sec3.status" name="status" id="status" @change="validate('status')" :class="{'border-red-500': validateError.status}">
                                                <option value="">[make selection]</option>
                                                <option value="1">single</option>
                                                <option value="2">married</option>
                                                <option value="3">separated</option>
                                                <option value="4">separated, but want my spouse to be the main beneficiary</option>
                                                <option value="5">divorced</option>
                                                <option value="6">widowed</option>
                                                <option value="7">in a civil union / domestic partnership</option>
                                            </select>
                                            <small x-text="validateError.status" class="text-red-500 block"></small>
                                        </div>
    
                                        <div class="flex flex-col">
                                            <p>Living Children:</p>
    
                                            <div>
                                                <input type="radio" name="children" value="1" class="mr-4" x-model="formData.sec3.children" @change="validate('children')"><label for="">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="children" value="2" class="mr-4" x-model="formData.sec3.children" @change="validate('children')"><label for="">No</label>
                                            </div>
                                            <small x-text="validateError.children" class="text-red-500 block"></small>
                                        </div>
    
                                        <div class="flex flex-col">
                                            <p>Living Grand-Children:</p>
    
                                            <div>
                                                <input type="radio" name="grandChildren" value="1" class="mr-4" x-model="formData.sec3.grandChildren" @change="validate('grandChildren')"><label for="">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="grandChildren" value="2" class="mr-4" x-model="formData.sec3.grandChildren" @change="validate('grandChildren')"><label for="">No</label>
                                            </div>
                                            <small x-text="validateError.grandChildren" class="text-red-500 block"></small>
                                        </div>
                                    </div>
    
                                    <!-- Partner/Spouse Details -->
                                    <div x-show="activeSubForm === 'partner'">
                                        <h1 class="text-4xl text-blue-500">Spouse/Partner Details</h1>
                                        <p>* = required information</p>
    
                                        <div>
                                            <label for="">Full Name</label>
                                            <input type="text" name="" id="" x-model="formData.sec3.fullName" @input="validate('fullName')">
                                            <small x-text="validateError.fullName" class="text-red-500 block"></small>
                                        </div>
    
                                        <div>
                                            <label for="">Relation</label>
                                            <select name="" id="" x-model="formData.sec3.relation" @change="validate('relation')">
                                                <option value="">[make selection]</option>
                                                <option value="wife">wife</option>
                                                <option value="husband">husband</option>
                                                <option value="common law wife">common law wife</option>
                                                <option value="common law husband">common law husband</option>
                                                <option value="partner">partner</option>
                                            </select>
                                            <small x-text="validateError.relation" class="text-red-500 block"></small>
                                        </div>
    
                                        <div>
                                            <p class="form-label mt-2">Gender pronoun:<span class="text-red-500">*</span></p>
                                            <div class="flex flex-col">
                                                <div>
                                                    <input type="radio" name="genderS" value="1" x-model="formData.sec3.partnerGender" @change="validate('partnerGender')" class="mr-4"><label for="">Male (he/his)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="genderS" value="2" x-model="formData.sec3.partnerGender" @change="validate('partnerGender')" class="mr-4"><label for="">Female (she/her)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="genderS" value="3" x-model="formData.sec3.partnerGender" @change="validate('partnerGender')" class="mr-4"><label for="">Neutral (they/their)</label>
                                                </div>
                                                <small x-text="validateError.partnerGender" class="text-red-500 block"></small>
                                            </div>
                                        </div>
                                    </div>
    
                                    <!-- Children -->
                                    <div x-show="activeSubForm === 'children'">
                                        <h1 class="text-4xl text-blue-500">Identify Children</h1>
                                        <p>* = required information</p>
                                        <template x-for="(item,childIndex) in formData.sec3.childDetails">
                                            <div class="mb-8">
                                                <div>
                                                    <label for=""><span x-show="childIndex >= 1" x-text="'#'+(childIndex+1)"></span> Child's Full Name</label>
                                                    <input x-model="formData.sec3.childDetails[childIndex].name" type="text">
                                                </div>
            
                                                <div>
                                                    <p class="form-label mt-2">Relationship:<span class="text-red-500">*</span></p>
                                                    <div class="flex flex-col">
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="1" class="mr-4"><label for="">Son</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="2" class="mr-4"><label for="">Daughter</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="3" class="mr-4"><label for="">Gender Neutral Child</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="4" class="mr-4"><label for="">Stepson</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="5" class="mr-4"><label for="">Stepdaughter</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relation'+childIndex" x-model="formData.sec3.childDetails[childIndex].relation" value="6" class="mr-4"><label for="">Gender neutral stepchild</label>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="mt-2">
                                                    <label for=""><span x-show="childIndex >= 1" x-text="'#'+(childIndex+1)"></span> Child's Date of birth</label>
                                                    <input type="date" x-model="formData.sec3.childDetails[childIndex].dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" max="2018-12-31">                                                    
                                                </div>                                                
                                                <button x-show="childIndex >= 1" @click="removeChild(childIndex)" class="bg-red-500 px-6 py-2 text-white hover:bg-red-600">Remove Child</button>
                                            </div>
                                        </template>
    
                                        <div>
                                            <button x-show="formData.sec3.numChildren < 5" @click="addChild($event)" class="bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add Child</button>
                                        </div>
                                    </div>
    
                                    <!-- Grand Child -->    
                                    <div x-show="activeSubForm === 'grandChildren'">
                                        <h1 class="text-4xl text-blue-500">Identify Grandchildren</h1>
    
                                        <p>Identifying all of your grandchildren is optional. It allows you to choose them later in this wizard if you decide to leave them some of your assets. If you do not plan on leaving anything specific to your grandChildren, you can simply skip this page.</p>
    
                                        <p>* = required information</p>
                                        
                                        <div>
                                            <div>
                                                <input type="radio" value="1" x-model="formData.sec3.grandChildrenDirect" id="">
                                                <label for="">I have grandchildren, but I am NOT leaving them something directly in my Will</label>
                                            </div>
                                            <div>
                                                <input type="radio" value="2" x-model="formData.sec3.grandChildrenDirect" id="">
                                                <label for="">I have grandchildren, and I MIGHT OR MIGHT NOT leave them something in my Will</label>
                                            </div>
                                        </div>
                                        <template x-for="(item,childIndex) in formData.sec3.grandChildDetails">
                                            <div class="mt-8 mb-8">
                                                <div>
                                                    <label for=""><span x-show="childIndex >= 1" x-text="'#'+(childIndex+1)"></span> Grandchild's Full Name</label>
                                                    <input x-model="formData.sec3.grandChildDetails[childIndex].name" type="text">
                                                </div>
            
                                                <div>
                                                    <p class="form-label mt-2">Relationship:<span class="text-red-500">*</span></p>
                                                    <div class="flex flex-col">
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="1" class="mr-4"><label for="">Son</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="2" class="mr-4"><label for="">Daughter</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="3" class="mr-4"><label for="">Gender Neutral Child</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="4" class="mr-4"><label for="">Stepson</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="5" class="mr-4"><label for="">Stepdaughter</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'relationGrandChild'+childIndex" x-model="formData.sec3.grandChildDetails[childIndex].relation" value="6" class="mr-4"><label for="">Gender neutral stepchild</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <label for=""><span x-show="childIndex >= 1" x-text="'#'+(childIndex+1)"></span> Child's Date of birth</label>
                                                    <input type="date" x-model="formData.sec3.grandChildDetails[childIndex].dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" max="2018-12-31">                                                    
                                                </div>  
                                                <button x-show="childIndex >= 1" @click="removeGrandChild(childIndex)" class="bg-red-500 px-6 py-2 text-white hover:bg-red-600">Remove Child</button>
                                            </div>
                                        </template>
    
                                        <div>
                                            <button x-show="formData.sec3.numChildren < 5" @click="addGrandChild($event)" class="bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add Child</button>
                                        </div>
                                    </div>

                                    <!-- Deceased Members -->
                                    <div x-show="activeSubForm === 'deceased'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Deceased Family Members</h1>
                                            <p>If any members of your immediate family (a spouse or a child) have passed away, you should list them here.</p>
                                            <div>
                                                <div>
                                                    <input type="radio" value="1" x-model="formData.sec3.deceased" id="">
                                                    <label for="">I DO NOT have any deceased family members</label>
                                                </div>
                                                <div>
                                                    <input type="radio" value="2" x-model="formData.sec3.deceased" id="">
                                                    <label for="">I have deceased family members</label>
                                                </div>                                                
                                            </div>
                                            <div x-show="formData.sec3.deceased==2">
                                                <div class="mt-6">
                                                    <template x-for="(item,childIndex) in formData.sec3.deceasedDetails">
                                                        <div class="mt-8 mb-8">
                                                            <p><span class="text-red-500">*</span> = required information</p>
                                                            <div>
                                                                <label for=""><span x-show="childIndex >= 1" x-text="'#'+(childIndex+1)"></span> Member's Full Name</label>
                                                                <input x-model="formData.sec3.deceasedDetails[childIndex].name" type="text">
                                                            </div>
                        
                                                            <div>                                                                
                                                                <label for="">Gender pronoun</label>
                                                                <select id="" x-model="formData.sec3.deceasedDetails[childIndex].gender">
                                                                    <option value="">[make selection]</option>
                                                                    <option value="Male (he/his)">Male (he/his)</option>
                                                                    <option value="Female (she/her)">Female (she/her)</option>
                                                                    <option value="Neutral (they/their)">Neutral (they/their)</option>                                                                    
                                                                </select>
                                                                <small x-text="validateError.gender" class="text-red-500 block"></small>
                                                            </div>
                                                            <div class="mt-2">
                                                                <label for="">Relationship</label>
                                                                <select id="" x-model="formData.sec3.deceasedDetails[childIndex].relation">
                                                                    <option value="">[make selection]</option>
                                                                    <option value="wife">wife</option>
                                                                    <option value="husband">husband</option>
                                                                    <option value="common law wife">common law wife</option>
                                                                    <option value="common law husband">common law husband</option>
                                                                    <option value="partner">partner</option>
                                                                    <option value="son">son</option>
                                                                    <option value="daughter">daughter</option>
                                                                    
                                                                </select>
                                                                <small x-text="validateError.gender" class="text-red-500 block"></small>
                                                            </div>  
                                                            <button x-show="childIndex >= 1" @click="removeDeceased(childIndex)" class="bg-red-500 px-6 py-2 text-white hover:bg-red-600">Remove</button>
                                                        </div>
                                                    </template>
                                                    <div>
                                                        <button x-show="formData.sec3.numChildren < 5" @click="addDeceased($event)" class="bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec4'">
                                    <div>
                                        <h1 class="text-4xl text-blue-500">Identify Others to be Included in your Will</h1>                                        
                                    </div>
                                    <div>
                                        <p class="mb-2">If there are other people or organizations to be included in your Will, you can name them now. You can also add more names later, as you are working through this wizard.</p>
                                        <p class="mb-2">You should not include your spouse/partner, children, or grandchildren on this page, because they would have been named in previous pages of this wizard.</p>
                                        <p class="mb-2">By listing the beneficiaries here, it makes it easier to select them later on for receiving a bequest. You are also able to set up a trust for them. They do not appear in your Will unless they are specifically selected in Section 7.</p>                                        
                                    </div>
                                    <div>                                      
                                        <div>
                                            <input type="radio" value="1" name="otherBeneficiaries" x-model="formData.sec4.otherBeneficiaries" id="">
                                            <label for="otherBeneficiaries">I have no other beneficiaries, or will add them later</label>
                                        </div>
                                        <div>
                                            <input type="radio" value="2" name="otherBeneficiaries" x-model="formData.sec4.otherBeneficiaries" id="">
                                            <label for="otherBeneficiaries">I would like to add beneficiaries now</label>
                                        </div>                                                                                                                             
                                    </div>
                                    <div x-show="formData.sec4.otherBeneficiaries==2">
                                        <div class="mt-6">
                                            <template x-for="(item,childIndex) in formData.sec4.beneficiaryDetails">
                                                <div class="mt-8 mb-8">
                                                    <p><span class="text-red-500">*</span> = required information</p>
                                                    <div>
                                                        <p class="form-label mt-2"><span class="text-red-500">*</span> Gender/Type:</p>
                                                        <div class="flex flex-col">
                                                            <div>
                                                                <input type="radio" :name="'genderBeneficiary'+childIndex" :id="'genderBeneficiary1'+childIndex" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="1" class="mr-4"><label :for="'genderBeneficiary1'+childIndex">Male</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" :name="'genderBeneficiary'+childIndex" :id="'genderBeneficiary2'+childIndex" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="2" class="mr-4"><label :for="'genderBeneficiary2'+childIndex">Female</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" :name="'genderBeneficiary'+childIndex" :id="'genderBeneficiary3'+childIndex" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="3" class="mr-4"><label :for="'genderBeneficiary3'+childIndex">Neutral</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" :name="'genderBeneficiary'+childIndex" :id="'genderBeneficiary4'+childIndex" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="4" class="mr-4"><label :for="'genderBeneficiary4'+childIndex">Charity/Org</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" :name="'genderBeneficiary'+childIndex" :id="'genderBeneficiary5'+childIndex" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="5" class="mr-4"><label :for="'genderBeneficiary5'+childIndex">Group</label>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="mb-2">
                                                        <div class="mt-2">
                                                            <label for=""><span class="text-red-500">*</span> Full Name</label>
                                                            <input x-model="formData.sec4.beneficiaryDetails[childIndex].name" type="text">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for=""><span class="text-red-500">*</span> Relationship</label>
                                                            <input x-model="formData.sec4.beneficiaryDetails[childIndex].relation" type="text">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for=""><span class="text-red-500">*</span> Address</label>
                                                            <input x-model="formData.sec4.beneficiaryDetails[childIndex].address" type="text">
                                                        </div>
                                                    </div>
                                                    <button x-show="childIndex >= 1" @click="removeBeneficiary(childIndex)" class="bg-red-500 px-6 py-2 text-white hover:bg-red-600">Remove</button>                                                   
                                                </div>
                                            </template>
                                            <div>
                                                <button x-show="formData.sec4.numBeneficiaries < 5" @click="addBeneficiary($event)" class="bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                            </div>                                                    
                                        </div>
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec5'">
                                    <div>
                                        <h1 class="text-4xl text-blue-500">Identify Guardians for Minor Children</h1>
                                    </div>
                                    <!-- Minor Children Details -->
                                    <div x-show="hasMinorChild()">
                                        <div>
                                            <p>It is very important that you name a guardian for each of your minor children. This person will be responsible for the care of your child if there are no parents available.</p>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex justify-start gap-6 border-2">
                                                <div class="w-4/12 p-4">
                                                    <div>
                                                        <p>Child's name	</p>
                                                    </div>                                                    
                                                </div>
                                                <div class="w-8/12 border-l-2 p-4">
                                                    <div>
                                                        <p>Guardians</p>
                                                    </div>                                                    
                                                </div>                                                
                                            </div>
                                            <template x-for="(item,childIndex) in formData.sec5.guardianDetails">
                                                <div class="flex justify-start gap-6 border-2">
                                                    <div class="w-4/12 p-4">
                                                        <div>
                                                            <p x-text="minorChildren[childIndex]"></p>
                                                        </div>                                                    
                                                    </div>
                                                    <div class="w-8/12 border-l-2 p-4">
                                                        <div>
                                                            <div>
                                                                <input type="hidden" :value="minorChildren[childIndex]" x-model="formData.sec5.guardianDetails[childIndex].childName">
                                                                <div>
                                                                    <label for="">Personal guardian's full name:</label>
                                                                    <input x-model="formData.sec5.guardianDetails[childIndex].name" type="text">
                                                                </div>
                                                                <div>
                                                                    <label for="">Reason for choosing this guardian:</label>
                                                                    <input x-model="formData.sec5.guardianDetails[childIndex].reason" type="text">
                                                                </div>
                                                                <div>
                                                                    <label for="">Alternate guardian's full name:</label>
                                                                    <input x-model="formData.sec5.guardianDetails[childIndex].alterName" type="text">
                                                                </div>
                                                            </div>
                                                        </div>                                                    
                                                    </div>                                                
                                                </div>    
                                            </template>                                        
                                        </div>
                                    </div>
                                    <!-- No Minor Children -->
                                    <div x-show="!hasMinorChild()">
                                        <div>
                                            <p class="mb-2">You have indicated that you have no minor children, so you do not need to identify any guardians.</p>
                                            <p class="mb-2">Click "NEXT" to continue...</p>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec6'">
                                    <!-- Sub section intro of section 6 -->
                                    <div x-show="activeSubForm === 'intro'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Executor</h1>
                                        </div>
                                        <div>
                                            <p class="mb-2">
                                                Here you name the person you would like to be the executor of your Will. This person will be responsible for carrying out your wishes as specified in your Will, including the distribution of your possessions to your beneficiaries.
                                            </p>
                                            <p class="mb-2">
                                                You must identify somebody here. Although it is common to list a single executor, you may name up to 3 executors who must then work together to carry out your wishes. On the next page you will be able to name alternate  executors to take the place of those unable to serve.
                                            </p>
                                            <p class="mb-2">
                                                We understand that you may need to talk to other people before naming an executor. However, if you are stuck, you can name a person now and come back and change it later.
                                            </p>
                                            <p class="mb-2">
                                                I would like the following to be the executor of my Will:
                                            </p>
                                            <p class="mb-2">
                                                <span class="text-red-500">*</span> = required information
                                            </p>
                                        </div>
                                        <div>
                                            <template x-for="(item,childIndex) in formData.sec6.executorDetails">
                                                <div class="mb-4">
                                                    <div>
                                                        <h2 x-text="getNumFormat(childIndex+1)+' Executor'" class="text-2xl text-blue-500"></h2>
                                                    </div>
                                                    <div>
                                                        <label for=""><span class="text-red-500">*</span> Full Name</label>
                                                        <input x-model="formData.sec6.executorDetails[childIndex].name" type="text">
                                                    </div>
                                                    <div>
                                                        <label for=""><span class="text-red-500">*</span> Relationship</label>
                                                        <input x-model="formData.sec6.executorDetails[childIndex].relation" type="text">
                                                    </div>
                                                    <div>
                                                        <label for=""><span class="text-red-500">*</span> Address</label>
                                                        <input x-model="formData.sec6.executorDetails[childIndex].address" type="text">
                                                    </div>
                                                    <button @click="removeExecutor(childIndex)" class="mt-2 bg-red-500 px-6 py-2 text-white hover:bg-red-600">
                                                        <span x-text="'DELETE ' + getNumFormat(childIndex+1) + ' EXECUTOR'"></span>
                                                    </button> 
                                                </div>
                                            </template>
                                            <div>
                                                <button x-show="formData.sec6.numExecutor < 5" @click="addExecutor($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sub section Alternate Executor of section 6 -->
                                    <div x-show="activeSubForm === 'alterExecutor'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Alternate Executor</h1>
                                        </div>
                                        <div x-show="getNumExecutors()==1">
                                            <p class="mb-2">On the previous page, I identified a single person (<span x-text="getExecutorNames()"></span>) to be the executor of my Will.</p>                                            
                                            <p class="mb-2">If for some reason <span x-text="getExecutorNames()"></span> is unable to serve as the executor of my Will:</p>
                                        </div>
                                        <div x-show="getNumExecutors()>1">
                                            <p class="mb-2">On the previous page, I identified multiple executors (<span x-text="getExecutorNames()"></span>), who must work together to carry out my wishes.</p>                                            
                                            <p class="mb-2">I choose:</p>
                                        </div>
                                        <div>
                                            <div class="flex flex-col">
                                                <div>
                                                    <input type="radio" name="alterOptions" x-model="formData.sec6.alterOptions" id="alterOpt1" value="1" class="mr-4"><label for="alterOpt1">NO ALTERNATES --- If for some reason ANY or ALL of these people are unable to serve, I do not want to identify any alternates</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="alterOptions" x-model="formData.sec6.alterOptions" id="alterOpt2" value="2" class="mr-4"><label for="alterOpt2">LIST OF REPLACEMENTS --- If for some reason ANY of these people are unable to serve, I would like the following to take their place, in the order listed below:</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="alterOptions" x-model="formData.sec6.alterOptions" id="alterOpt3" value="3" class="mr-4"><label for="alterOpt3">ALTERNATE PLAN --- If for some reason ALL of these people are unable to serve, I would like the following to take their place (if I identify more than 1 person below, then they must work together to carry out my wishes):</label>
                                                </div>                                                                                                            
                                            </div>
                                            <div x-show="formData.sec6.alterOptions!=1">
                                                <div class="mt-8">
                                                    <template x-for="(item,childIndex) in formData.sec6.alterExecutorDetails">
                                                        <div class="mb-4">
                                                            <div>
                                                                <h2 x-text="getNumFormat(childIndex+1)+' Alternate Executor'" class="text-2xl text-blue-500"></h2>
                                                            </div>
                                                            <div>
                                                                <label for=""><span class="text-red-500">*</span> Full Name</label>
                                                                <input x-model="formData.sec6.alterExecutorDetails[childIndex].name" type="text">
                                                            </div>
                                                            <div>
                                                                <label for=""><span class="text-red-500">*</span> Relationship</label>
                                                                <input x-model="formData.sec6.alterExecutorDetails[childIndex].relation" type="text">
                                                            </div>
                                                            <div>
                                                                <label for=""><span class="text-red-500">*</span> Address</label>
                                                                <input x-model="formData.sec6.alterExecutorDetails[childIndex].address" type="text">
                                                            </div>
                                                            <button x-show="childIndex>0" @click="removeAlterExecutor(childIndex)" class="mt-2 bg-red-500 px-6 py-2 text-white hover:bg-red-600">
                                                                <span x-text="'DELETE ' + getNumFormat(childIndex+1) + ' ALTERNATE'"></span>
                                                            </button> 
                                                        </div>
                                                    </template>
                                                    <div x-show="formData.sec6.numAlterExecutor < 2">
                                                        <button @click="addAlterExecutor($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec7'">
                                    <!-- Sub section intro of section 7 -->
                                    <div x-show="activeSubForm === 'intro'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Distribute Your Possessions</h1>
                                            <p class="italic font-bold">You are now ready to specify how you wish your possessions to be distributed.</p>
                                            <h2 class="mt-8 italic text-2xl text-blue-500">Remember:</h2>
                                        </div>
                                        <div>
                                            <ul class="list-disc pl-8">
                                                <li>
                                                    <p>To reduce the likelihood of your Will being contested in a court of law, be as complete and unambiguous in your answers as possible.</p>
                                                </li>
                                                <li>
                                                    <p>While answering the questions, if you need general assistance on the section, just read the Common Questions which appear on every page. If you don't see the questions, simply click on the big <span class="font-bold text-4xl">?</span> near the top of the page.</p>                                                    
                                                </li>
                                                <li>
                                                   <p>Specific help for parts of a page that may be unclear is available by tapping (or moving your mouse over) the small <span class="font-bold">?</span> symbol which appears next to some questions.</p> 
                                                </li>
                                                <li>
                                                    <p>You can come back at any time to revise your answers and keep your Will up to date, free of charge.</p>
                                                </li>
                                            </ul>
                                            <h2 class="mt-8 text-2xl text-blue-500">Click on the "NEXT" button below to continue.</h2>
                                        </div>
                                    </div>
                                    <!-- Sub section Bequests to Charities of section 7 -->
                                    <div x-show="activeSubForm === 'charities'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Bequests to Charities</h1>                                            
                                        </div>
                                        <div>
                                            <div class="mt-4">
                                                <p class="mb-2">
                                                Before we get started with distributing your possessions, have you given any thought to whether you would like to leave something to charity?
                                                </p>
                                                <p class="mb-2">
                                                Many people like to leave a gift to charity in their Will because they care about important causes. Do you want to make a gift to charity in your Will to support causes that have been important in your life? If you wish, you can use this page to leave a fixed sum, a specific item, or a percentage of your estate to one or more specific charities.
                                                </p>                                            
                                            </div>
                                            <div class="flex flex-col">
                                                <div>
                                                    <input type="radio" name="charitableDonation" x-model="formData.sec7.charitableDonation" id="charitableDonation1" value="1" class="mr-4"><label for="charitableDonation1">I do not want to specify a charitable donation in my Will.</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="charitableDonation" x-model="formData.sec7.charitableDonation" id="charitableDonation2" value="2" class="mr-4"><label for="charitableDonation2">I would like to include a charitable donation in my Will.</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="charitableDonation" x-model="formData.sec7.charitableDonation" id="charitableDonation3" value="3" class="mr-4"><label for="charitableDonation3">Undecided. Let me come back to this later.</label>
                                                </div>                                                                                                            
                                            </div>
                                            <div x-show="formData.sec7.charitableDonation==2">
                                                <div>
                                                    <template x-for="(item,childIndex) in formData.sec7.bequestDetails">
                                                        <div class="mt-10 flex">
                                                            <div class="w-2/12 p-4 border-2">
                                                                <p x-text="childIndex+1"></p>
                                                            </div>
                                                            <div class="w-10/12 p-4 border-2 border-l-0">
                                                                <p><span class="text-red-500">*</span> = required information</p>
                                                                <label :for="'bequestType'+childIndex">I would like to give</label>
                                                                <select x-model="formData.sec7.bequestDetails[childIndex].type" :id="'bequestType'+childIndex">                                                            
                                                                    <option value="1">Specific amount of money</option>
                                                                    <option value="2">Percentage of my estate</option>
                                                                    <option value="3">Specific asset (e.g. my car)</option>                                                            
                                                                </select>
                                                                <div x-show="formData.sec7.bequestDetails[childIndex].type==1">
                                                                    <label for=""><span class="text-red-500">*</span> Enter the amount</label>
                                                                    <input x-model="formData.sec7.bequestDetails[childIndex].amount" type="text">
                                                                </div>
                                                                <div x-show="formData.sec7.bequestDetails[childIndex].type==2">
                                                                    <label for=""><span class="text-red-500">*</span> Enter the percentage</label>
                                                                    <input x-model="formData.sec7.bequestDetails[childIndex].percentage" type="text">
                                                                </div>
                                                                <div x-show="formData.sec7.bequestDetails[childIndex].type==3">
                                                                    <label for=""><span class="text-red-500">*</span> Description</label>
                                                                    <textarea x-model="formData.sec7.bequestDetails[childIndex].asset" class="h-1/5 p-2"></textarea>                                                                    
                                                                </div>
                                                                <div>
                                                                    <label for=""><span class="text-red-500">*</span> To the charity</label>
                                                                    <input x-model="formData.sec7.bequestDetails[childIndex].charityName" type="text">
                                                                </div>
                                                                <button x-show="childIndex>0" @click="removeBequest(childIndex)" class="mt-2 bg-red-500 px-6 py-2 text-white hover:bg-red-600">
                                                                    DELETE
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div x-show="formData.sec7.numBequests < 5">
                                                        <button @click="addBequest($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sub section Trusts for Pets of section 7 -->
                                    <div x-show="activeSubForm === 'petTrusts'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Trusts for Pets</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">
                                            If you have pets, you may wish to create a Trust to be used for their care after you have passed away.
                                            </p>                                                                                    
                                        </div>
                                        <div class="flex flex-col">
                                            <div>
                                                <input type="radio" name="petTrust" x-model="formData.sec7.petTrust" id="petTrust1" value="1" class="mr-4"><label for="petTrust1">I do not want to create a Trust for the care of a pet.</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="petTrust" x-model="formData.sec7.petTrust" id="petTrust2" value="2" class="mr-4"><label for="petTrust2">I would like to create a Trust for the care of a pet.</label>
                                            </div>                                            
                                        </div>
                                        <div x-show="formData.sec7.petTrust==2">
                                            <div>
                                                <template x-for="(item,childIndex) in formData.sec7.petDetails">
                                                    <div class="mt-10 flex">
                                                        <div class="w-2/12 p-4 border-2">
                                                            <p x-text="childIndex+1"></p>
                                                        </div>
                                                        <div class="w-10/12 p-4 border-2 border-l-0">
                                                            <p><span class="text-red-500">*</span> = required information</p>                                                            
                                                            <div class="mb-2">
                                                                <label for=""><span class="text-red-500">*</span> Name of Pet</label>
                                                                <input x-model="formData.sec7.petDetails[childIndex].name" type="text">
                                                                <p class="text-xs">Enter the name that is used to uniquely identify the pet, so that there is no confusion over which pet you are referring to.</p>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for=""><span class="text-red-500">*</span> Type of Pet</label>
                                                                <input x-model="formData.sec7.petDetails[childIndex].type" type="text">
                                                                <p class="text-xs">Be as specific as you can. For example, "Chocolate Labrador Retriever".</p>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for=""><span class="text-red-500">*</span> Amount in the Trust</label>
                                                                <input x-model="formData.sec7.petDetails[childIndex].amount" type="text">
                                                                <p class="text-xs">Enter the exact amount of money, including the currency. For example, "$1,000 United States dollars".</p>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for=""><span class="text-red-500">*</span> Caretaker</label>
                                                                <input x-model="formData.sec7.petDetails[childIndex].caretaker" type="text">
                                                                <p class="text-xs">Uniquely identify the person that you wish to care for this pet.</p>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label for=""><span class="text-red-500">*</span> Alternate Caretaker</label>
                                                                <input x-model="formData.sec7.petDetails[childIndex].alterCaretaker" type="text">
                                                                <p class="text-xs">This person would take on the role if your first choice was unable or unwilling to serve.</p>
                                                            </div>
                                                            <button x-show="childIndex>0" @click="removePet(childIndex)" class="mt-2 bg-red-500 px-6 py-2 text-white hover:bg-red-600">
                                                                DELETE
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="formData.sec7.numPets < 5">
                                                    <button @click="addPet($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sub section Distribute My Possessions of section 7 -->
                                    <div x-show="activeSubForm === 'possessionsDist'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Distribute My Possessions</h1>                                            
                                        </div>
                                        <div class="mt-2 flex flex-col">
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist1" value="1" class="mr-4"><label for="possessionDist1">Leave everything I own to multiple beneficiaries.</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist2" value="2" class="mr-4"><label for="possessionDist2">I would like to leave specific items to specific beneficiaries, and leave the rest to multiple beneficiaries.</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist3" value="3" class="mr-4"><label for="possessionDist3">Leave everything I own to the following beneficiary:</label>
                                            </div>
                                            <div class="w-6/12">
                                                <label for=""><span class="text-red-500">*</span> Beneficiary:</label>
                                                <select x-model="formData.sec7.everythingBenefIndex" >
                                                    <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                        <option :value="benf.value" :selected="benf.value == formData.sec7.everythingBenefIndex" x-text="benf.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist4" value="4" class="mr-4"><label for="possessionDist4">I would like to leave specific items to specific beneficiaries, and leave the rest to the following beneficiary:</label>
                                            </div>
                                            <div class="w-6/12">
                                                <label for=""><span class="text-red-500">*</span> Beneficiary:</label>
                                                <select x-model="formData.sec7.specificThingBenefIndex" >
                                                    <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                        <option :value="benf.value" :selected="benf.value == formData.sec7.specificThingBenefIndex" x-text="benf.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist5" value="5" class="mr-4"><label for="possessionDist5">I would like to leave specific items to specific beneficiaries, and let me describe how to leave the rest.</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist6" value="6" class="mr-4"><label for="possessionDist6">None of the above.  Let me describe in detail how I would like to distribute my possessions.</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="possessionDist" x-model="formData.sec7.possessionDist" id="possessionDist7" value="7" class="mr-4"><label for="possessionDist7">Undecided</label>
                                            </div>                                            
                                        </div>
                                    </div>
                                    <!-- Sub section Divide Possessions Between Multiple Beneficiaries of section 7 -->
                                    <div x-show="activeSubForm === 'divideEqual'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Divide Possessions Between Multiple Beneficiaries</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p x-show="formData.sec7.possessionDist==1" class="mb-2">
                                                I would like my possessions to be divided between my multiple beneficiaries in the following way:
                                            </p>
                                            <p x-show="formData.sec7.possessionDist==2" class="mb-2">
                                                I would like the rest of my possessions to be divided between my multiple beneficiaries in the following way:
                                            </p>
                                            <p><span class="text-red-500">*</span> = required information</p>
                                            <div class="mt-2 flex flex-col">
                                                <div>
                                                    <input type="radio" name="shareExp" x-model="formData.sec7.shareExp" id="shareExp1" value="1" class="mr-4"><label for="shareExp1">Express shares below as fractions (total must equal 1)</label>
                                                </div>
                                                <div>
                                                    <input type="radio" name="shareExp" x-model="formData.sec7.shareExp" id="shareExp2" value="2" class="mr-4"><label for="shareExp2">Express shares below as percentages (total must equal 100)</label>
                                                </div>                                                                                     
                                            </div>

                                            <div class="mt-6">
                                                <template x-for="(item,childIndex) in formData.sec4.beneficiaryDetails">
                                                    <div class="mt-10 flex">
                                                        <div class="w-2/12 p-4 border-2">
                                                            <p x-text="childIndex+1"></p>
                                                        </div>
                                                        <div class="w-6/12 p-4 border-2 border-l-0">
                                                            <p><span class="text-red-500">*</span> = required information</p>
                                                            <div>
                                                                <label for=""><span class="text-red-500">*</span> Full Name</label>
                                                                <input x-model="formData.sec4.beneficiaryDetails[childIndex].name" type="text">
                                                            </div>
                                                            <div>
                                                                <p class="form-label mt-2"><span class="text-red-500">*</span> Gender/Type:</p>
                                                                <div class="flex flex-col">
                                                                    <div>
                                                                        <input type="radio" :name="'genderBeneficiary2'+childIndex" id="genderBeneficiary11" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="1" class="mr-4"><label for="genderBeneficiary11">Male</label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="radio" :name="'genderBeneficiary2'+childIndex" id="genderBeneficiary22" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="2" class="mr-4"><label for="genderBeneficiary22">Female</label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="radio" :name="'genderBeneficiary2'+childIndex" id="genderBeneficiary33" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="3" class="mr-4"><label for="genderBeneficiary33">Neutral</label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="radio" :name="'genderBeneficiary2'+childIndex" id="genderBeneficiary44" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="4" class="mr-4"><label for="genderBeneficiary44">Charity/Org</label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="radio" :name="'genderBeneficiary2'+childIndex" id="genderBeneficiary55" x-model="formData.sec4.beneficiaryDetails[childIndex].gender" value="5" class="mr-4"><label for="genderBeneficiary55">Group</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-2">                                                                
                                                                <div class="mt-2">
                                                                    <label for=""><span class="text-red-500">*</span> Relationship</label>
                                                                    <input x-model="formData.sec4.beneficiaryDetails[childIndex].relation" type="text">
                                                                </div>
                                                                <div class="mt-2">
                                                                    <label for=""><span class="text-red-500">*</span> Address</label>
                                                                    <input x-model="formData.sec4.beneficiaryDetails[childIndex].address" type="text">
                                                                </div>
                                                            </div>
                                                            <button x-show="childIndex >= 1" @click="removeBeneficiary(childIndex)" class="bg-red-500 px-6 py-2 text-white hover:bg-red-600">Remove</button>
                                                        </div>
                                                        <div class="w-4/12 p-4 border-2 border-l-0">
                                                            <div x-show="formData.sec7.shareExp==1">
                                                                <div>
                                                                    Beneficiary's Share, expressed as a fraction (e.g. "1/3")
                                                                </div>
                                                                <div>
                                                                    <div class="mt-4">
                                                                        <label for=""><span class="text-red-500">*</span> Share</label>
                                                                        <input x-model="formData.sec4.beneficiaryDetails[childIndex].eqShare" type="text">                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div x-show="formData.sec7.shareExp==2">
                                                                <div>
                                                                    Beneficiary's Share, expressed as a percentage (e.g. "20.5")
                                                                </div>
                                                                <div>
                                                                    <div class="mt-4">
                                                                        <label for=""><span class="text-red-500">*</span> Share</label>
                                                                        <input x-model="formData.sec4.beneficiaryDetails[childIndex].eqShare" type="text">
                                                                        <p class="text-xs">Percent</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div class="mt-2">
                                                    <button x-show="formData.sec4.numBeneficiaries < 5" @click="addBeneficiary($event)" class="bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add Beneficiary</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Sub section Make Bequests of section 7 -->
                                    <div x-show="activeSubForm === 'makeBequests'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Make Bequests</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">
                                            I would like to leave the following specific items to specific beneficiaries. Any of my possessions not specifically described here will go to my multiple main beneficiaries.
                                            </p>
                                        </div>
                                        <div class="p-4 border-2">
                                            <template x-for="(item,childIndex) in formData.sec7.specificsDetails">
                                                <div class="mt-10 flex">
                                                    <div class="w-2/12 p-4 border-2">
                                                        <p x-text="childIndex+1"></p>
                                                    </div>
                                                    <div class="w-10/12 p-4 border-2 border-l-0">
                                                        <p class="text-2xl mb-2">Add Bequest</p>
                                                        <p class="mb-2"><span class="text-red-500">*</span> = required information</p>
                                                        <p class="my-2">STEP 1 - Choose whether this bequest is a:</p>                                                        
                                                        <div>                                                            
                                                            <div class="flex flex-col">
                                                                <div>
                                                                    <input type="radio" :name="'specType2'+childIndex" id="specType11" x-model="formData.sec7.specificsDetails[childIndex].type" value="1" class="mr-4"><label for="specType11">Simple gift (one item to one person)</label>
                                                                </div>
                                                                <div>
                                                                    <input type="radio" :name="'specType2'+childIndex" id="specType22" x-model="formData.sec7.specificsDetails[childIndex].type" value="2" class="mr-4"><label for="specType22">Detailed description</label>
                                                                </div>                                                                
                                                            </div>
                                                            <div>
                                                                <p class="my-2">STEP 2 - Describe the bequest:</p>
                                                                <div x-show="formData.sec7.specificsDetails[childIndex].type==1">
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> I would like to give:</label>
                                                                        <input x-model="formData.sec7.specificsDetails[childIndex].gift" type="text">                                                                    
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> To the beneficiary:</label>
                                                                        <select x-model="formData.sec7.specificsDetails[childIndex].giftBenefIndex" >
                                                                            <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                                                <option :value="benf.value" :disabled="benf.value==-1" :selected="benf.value == formData.sec7.specificsDetails[childIndex].giftBenefIndex" x-text="benf.name"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label for="">Alternate  beneficiary:</label>
                                                                        <select x-model="formData.sec7.specificsDetails[childIndex].alterGiftBenefIndex">
                                                                            <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                                                <option :value="benf.value" :selected="benf.value == formData.sec7.specificsDetails[childIndex].alterGiftBenefIndex" x-text="benf.name"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div x-show="formData.sec7.specificsDetails[childIndex].type==2">
                                                                    <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of all beneficiaries you include in your description.</p>
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> Description:</label>
                                                                        <textarea class="h-1/5 p-2" x-model="formData.sec7.specificsDetails[childIndex].description"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                        <button @click="removeSpecific(childIndex)" class="bg-red-500 mt-2 px-6 py-2 text-white hover:bg-red-600">Delete</button>
                                                    </div>                                                    
                                                </div>
                                            </template>
                                            <div x-show="formData.sec7.numSpecifics < 10">
                                                <button @click="addSpecific($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sub section Make Provisions For Multiple Beneficiaries of section 7 -->
                                    <div x-show="activeSubForm === 'multiBenefProvisions'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Make Provisions For Multiple Beneficiaries</h1>                                            
                                        </div>
                                        <template x-for="(item,childIndex) in formData.sec4.beneficiaryDetails">
                                            <div class="border-2 mt-4 p-4">
                                                <div>
                                                    <p>If my <span x-text="item.relation"></span>, <span x-text="item.name"></span>, does not survive me by thirty (30) days, then:</p>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="flex flex-col">
                                                        <!-- 'multiBenefProvisions' => [['radio'=1,'alterBenefIndex'=>-1,'shareDesc'=>'']] -->
                                                        <div class="mb-2">
                                                            <input type="radio" :name="'multiBen2'+childIndex" :id="'multiBen11'+childIndex" x-model="formData.sec7.multiBenefProvisions[childIndex].radio" value="1" class="mr-4"><label :for="'multiBen11'+childIndex">Divide his share equally between his own surviving children</label>
                                                        </div>
                                                        <div class="mb-2">
                                                            <input type="radio" :name="'multiBen2'+childIndex" :id="'multiBen22'+childIndex" x-model="formData.sec7.multiBenefProvisions[childIndex].radio" value="2" class="mr-4"><label :for="'multiBen22'+childIndex">Divide his share equally between my other surviving beneficiaries</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'multiBen2'+childIndex" :id="'multiBen33'+childIndex" x-model="formData.sec7.multiBenefProvisions[childIndex].radio" value="3" class="mr-4"><label :for="'multiBen33'+childIndex">Leave his share to the following alternate  beneficiary:</label>
                                                        </div>
                                                        <div class="mb-2" x-init="updateState()" class="mt-2 w-6/12">                                                                                                            
                                                            <label for=""><span class="text-red-500">*</span> Beneficiary:</label>
                                                            <select x-model="formData.sec7.multiBenefProvisions[childIndex].alterBenefIndex" >
                                                                <template x-for="(benf, benefIndex) in getAlterBenefs(childIndex)">
                                                                    <option :value="benf.value" :selected="benf.value == formData.sec7.multiBenefProvisions[childIndex].alterBenefIndex" x-text="benf.name"></option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <input type="radio" :name="'multiBen2'+childIndex" :id="'multiBen44'+childIndex" x-model="formData.sec7.multiBenefProvisions[childIndex].radio" value="4" class="mr-4"><label :for="'multiBen44'+childIndex">Distribute his share in the following way:</label>
                                                        </div>
                                                        <div class="mb-2"><p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of all beneficiaries you include in your description.</p></div>
                                                        <div class="mb-2">
                                                            <textarea class="h-1/5 p-2" x-model="formData.sec7.multiBenefProvisions[childIndex].shareDesc"></textarea>
                                                        </div>
                                                        <div class="mb-2">
                                                            <input type="radio" :name="'multiBen2'+childIndex" :id="'multiBen55'+childIndex" x-model="formData.sec7.multiBenefProvisions[childIndex].radio" value="5" class="mr-4"><label :for="'multiBen55'+childIndex">Undecided</label>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </template>                                        

                                    </div>

                                    
                                    <!-- Sub section Name Alternate Beneficiary Intro of section 7 -->
                                    <div x-show="activeSubForm === 'alterBenefIntro'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Name Alternate Beneficiary</h1>                                            
                                        </div>
                                        <div x-show="formData.sec7.possessionDist==2" class="mt-4">
                                            <p class="mb-2">You have identified that your multiple main beneficiaries should receive all of your possessions, with the exception of the specific bequests you described earlier.</p>
                                            <p class="mb-2">In the event that none of your multiple main beneficiaries survive you by thirty (30) days, you now have an opportunity to specify how you would like to distribute everything that they would have received.</p>
                                        </div>
                                        <div x-show="formData.sec7.possessionDist==4" class="mt-4">
                                            <p class="mb-2">You have identified your main beneficiary (<span x-text="getBenefNameByIndex(formData.sec7.specificThingBenefIndex)"></span>) to receive all of your possessions, with the exception of the specific bequests you just described.</p>
                                            <p class="mb-2">In the event that your main beneficiary (<span x-text="getBenefNameByIndex(formData.sec7.specificThingBenefIndex)"></span>) does not survive you by thirty (30) days, you now have an opportunity to specify how you would like to distribute everything that would have gone to your main beneficiary (<span x-text="getBenefNameByIndex(formData.sec7.specificThingBenefIndex)"></span>).</p>
                                        </div>
                                    </div>


                                    <!-- Sub section Name Alternate Beneficiary of section 7 -->
                                    <div x-show="activeSubForm === 'alterBenef'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Name Alternate Beneficiary</h1>                                            
                                        </div>
                                        <div x-show="[1,2].includes(+formData.sec7.possessionDist)" class="mt-4">
                                            <p class="mb-2">If none of my multiple main beneficiaries survive me by thirty (30) days, then:</p>
                                        </div>
                                        <div x-show="[3,4].includes(+formData.sec7.possessionDist)" class="mt-4">
                                            <p class="mb-2">If my main beneficiary (<span x-text="getBenefNameByIndex(formData.sec7.specificThingBenefIndex)"></span>) does not survive me by thirty (30) days, then:</p>
                                        </div>
                                        <div class="mt-2">
                                        <div class="flex flex-col">
                                            <!-- 'alterBenefProvisions'=> [['radio'=>1,'everythingAlterBenefIndex'=>-1,'restAllBenefIndex'=>-1]], -->                                            
                                            <div x-show="[1,2].includes(+formData.sec7.possessionDist)" class="mb-2">
                                                <input type="radio" name="alterBen2" id="alterBen11" x-model="formData.sec7.alterBenefProvisions.radio" value="1" class="mr-4"><label for="alterBen11">For each of my multiple main beneficiaries, divide their individual share equally between their own children</label>
                                            </div>
                                            <div class="mb-2">
                                                <input type="radio" name="alterBen2" id="alterBen22" x-model="formData.sec7.alterBenefProvisions.radio" value="2" class="mr-4"><label for="alterBen22">Leave everything that my multiple main beneficiaries would have received to the following alternate beneficiary:</label>
                                                <div x-init="updateState()" class="w-6/12">                                                                                                            
                                                    <label for=""><span class="text-red-500">*</span> Beneficiary:</label>
                                                    <select x-model="formData.sec7.alterBenefProvisions.everythingAlterBenefIndex" >
                                                        <template x-for="(benf, benefIndex) in formData.sec4.beneficiaryDetails">
                                                            <option :value="benefIndex" :selected="benefIndex == formData.sec7.alterBenefProvisions.everythingAlterBenefIndex" x-text="benf.name"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="radio" name="alterBen2" id="alterBen33" x-model="formData.sec7.alterBenefProvisions.radio" value="3" class="mr-4"><label for="alterBen33">I would like to leave specific items that my multiple main beneficiaries would have received to other specific beneficiaries, and leave the rest to the following alternate  beneficiary:</label>
                                                <div x-init="updateState()" class="w-6/12">                                                                                                            
                                                    <label for=""><span class="text-red-500">*</span> Beneficiary:</label>
                                                    <select x-model="formData.sec7.alterBenefProvisions.restAllBenefIndex" >
                                                        <template x-for="(benf, benefIndex) in formData.sec4.beneficiaryDetails">
                                                            <option :value="benefIndex" :selected="benefIndex == formData.sec7.alterBenefProvisions.restAllBenefIndex" x-text="benf.name"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="radio" name="alterBen2" id="alterBen44" x-model="formData.sec7.alterBenefProvisions.radio" value="4" class="mr-4"><label for="alterBen44">I would like to leave specific items that my multiple main beneficiaries would have received to other specific beneficiaries, and let me describe how to leave the rest</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="alterBen2" id="alterBen55" x-model="formData.sec7.alterBenefProvisions.radio" value="5" class="mr-4"><label for="alterBen55">None of the above.  Let me describe how I would like to distribute everything that my multiple main beneficiaries would have received</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="alterBen2" id="alterBen66" x-model="formData.sec7.alterBenefProvisions.radio" value="6" class="mr-4"><label for="alterBen66">Undecided</label>
                                            </div>                                            
                                        </div>                                            
                                        </div>
                                    </div>                                    

                                    <!-- Sub section Make An Alternative Plan of section 7 -->
                                    <div x-show="activeSubForm === 'makeAlterPlan'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Make An Alternative Plan</h1>                                            
                                        </div>
                                        <div class="mt-2">
                                            <p >You have decided to make an alternative plan, to come into effect if your multiple main beneficiaries do not survive you.</p>
                                        </div>
                                        <div class="mt-2">
                                            <p x-show="['3'].includes(formData.sec7.alterBenefProvisions.radio)">In particular, you have indicated that if your multiple main beneficiaries do not survive you, then you would like all of your possessions to go to <strong x-text="formData.sec4.beneficiaryDetails[formData.sec7.alterBenefProvisions.restAllBenefIndex].name"></strong>, with the exception of some specific items.</p>
                                            <p x-show="['4'].includes(formData.sec7.alterBenefProvisions.radio)">You will be able to distribute all of your possessions in a very specific way, but this will only be applicable if your multiple main beneficiaries do not survive you by thirty (30) days.</p>
                                        </div>
                                        <div class="mt-2">
                                            <p x-show="['3'].includes(formData.sec7.alterBenefProvisions.radio)">You now have an opportunity to specify those items and their corresponding beneficiaries. Take care when specifying these items to be as specific as possible. Also, keep in mind that this will only be applicable if your multiple main beneficiaries do not survive you by thirty (30) days.</p>
                                            <p x-show="['4'].includes(formData.sec7.alterBenefProvisions.radio)">Take care when creating this plan to be as specific as possible. At the end you will name the beneficiary who will inherit the remainder of your possessions (everything other than those items specified), if your multiple main beneficiaries do not survive you by thirty (30) days.</p>
                                        </div>
                                    </div>
                                    <!-- Sub section Make Bequests (Alternative Plan) of section 7 -->
                                    <div x-show="activeSubForm === 'makeAlterPlanBequests'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Make Bequests (Alternative Plan)</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">In the event that my multiple main beneficiaries do not survive me by thirty (30) days, I would like to leave all of my possessions to <strong x-text="formData.sec4.beneficiaryDetails[formData.sec7.alterBenefProvisions.restAllBenefIndex].name"></strong>, with the exception of the following bequests:</p>
                                        </div>
                                        <!-- 'alterSpecificsDetails' => [['type'=>1,'gift'=>'','description'=>'','giftBenefIndex'=>-1,'alterGiftBenefIndex'=>-1]], -->
                                        <div class="p-4 border-2">
                                            <template x-for="(item,childIndex) in formData.sec7.alterSpecificsDetails">
                                                <div class="mt-10 flex">
                                                    <div class="w-2/12 p-4 border-2">
                                                        <p x-text="childIndex+1"></p>
                                                    </div>
                                                    <div class="w-10/12 p-4 border-2 border-l-0">
                                                        <p class="text-2xl mb-2">Add Bequest</p>
                                                        <p class="mb-2"><span class="text-red-500">*</span> = required information</p>
                                                        <p class="my-2">STEP 1 - Choose whether this bequest is a:</p>                                                        
                                                        <div>                                                            
                                                            <div class="flex flex-col">
                                                                <div>
                                                                    <input type="radio" :name="'specTypeAlter2'+childIndex" id="specTypeAlter11" x-model="formData.sec7.alterSpecificsDetails[childIndex].type" value="1" class="mr-4"><label for="specTypeAlter11">Simple gift (one item to one person)</label>
                                                                </div>
                                                                <div>
                                                                    <input type="radio" :name="'specTypeAlter2'+childIndex" id="specTypeAlter22" x-model="formData.sec7.alterSpecificsDetails[childIndex].type" value="2" class="mr-4"><label for="specTypeAlter22">Detailed description</label>
                                                                </div>                                                                
                                                            </div>
                                                            <div>
                                                                <p class="my-2">STEP 2 - Describe the bequest:</p>
                                                                <div x-show="formData.sec7.alterSpecificsDetails[childIndex].type==1">
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> I would like to give:</label>
                                                                        <textarea class="h-1/5 p-2" x-model="formData.sec7.alterSpecificsDetails[childIndex].gift"></textarea>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> To the beneficiary:</label>
                                                                        <select x-model="formData.sec7.alterSpecificsDetails[childIndex].giftBenefIndex" >
                                                                            <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                                                <option :value="benf.value" :disabled="benf.value==-1" :selected="benf.value == formData.sec7.alterSpecificsDetails[childIndex].giftBenefIndex" x-text="benf.name"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label for="">Alternate  beneficiary:</label>
                                                                        <select x-model="formData.sec7.alterSpecificsDetails[childIndex].alterGiftBenefIndex">
                                                                            <template x-for="(benf, benefIndex) in beneficiaryNames">
                                                                                <option :value="benf.value" :selected="benf.value == formData.sec7.alterSpecificsDetails[childIndex].alterGiftBenefIndex" x-text="benf.name"></option>
                                                                            </template>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div x-show="formData.sec7.alterSpecificsDetails[childIndex].type==2">
                                                                    <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of all beneficiaries you include in your description.</p>
                                                                    <div class="mt-2">
                                                                        <label for=""><span class="text-red-500">*</span> Description:</label>
                                                                        <textarea class="h-1/5 p-2" x-model="formData.sec7.alterSpecificsDetails[childIndex].description"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                        <button x-show="formData.sec7.numAlterSpecifics > 1" @click="removeAlterSpecific(childIndex)" class="bg-red-500 mt-2 px-6 py-2 text-white hover:bg-red-600">Delete</button>
                                                    </div>                                                    
                                                </div>
                                            </template>
                                            <div x-show="formData.sec7.numAlterSpecifics < 10">
                                                <button @click="addAlterSpecific($event)" class="mt-2 bg-green-500 px-6 py-2 text-white hover:bg-green-600">Add</button>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!-- Sub section Describe How To Distribute (Alternative Plan) of section 7 -->
                                    <div x-show="activeSubForm === 'describeAlterPlan'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Describe How To Distribute (Alternative Plan)</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">In case your primary beneficiary cannot inherit your estate, this page allows you to describe the distribution of your estate. Everything that is left over, or cannot be delivered to a chosen beneficiary, is called the "Residual Estate" and your plans for this are described on the next page.</p>
                                            <p class="mb-2">If my multiple main beneficiaries do not survive me by thirty (30) days and my alternative plan comes into effect, I would like the following description to represent how I would like to distribute everything that my multiple main beneficiaries would have received:</p>
                                            <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of any beneficiaries you include in your description.</p>
                                            <div class="mb-2">                                            
                                                <textarea class="h-40 p-2" x-model="formData.sec7.describeAlterDesc"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sub section Describe Specific Bequests (if any) of section 7 -->
                                    <div x-show="activeSubForm === 'descSpecificBequests'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Describe Specific Bequests (if any)</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">If my multiple main beneficiaries do not survive me by thirty (30) days and my alternative plan comes into effect, I would like the following description to represent how I would like to distribute all of my possessions, with the exception of those items previously described.  This includes those items left to any beneficiaries who do not survive me by thirty (30) days, and for which there is no alternate  beneficiary who survives me by thirty (30) days.</p>
                                            <p class="mb-2">I would like to distribute specific bequests in the following way (OPTIONAL):</p>
                                            <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of any beneficiaries you include in your description.</p>
                                        </div>
                                        <div class="mt-2">
                                            <label for="">I would like to give:</label>
                                            <textarea class="h-40 p-2" x-model="formData.sec7.descSpecificBequest"></textarea>
                                        </div>
                                    </div>



                                    <!-- Sub section Residual Beneficiary (Alternative Plan) of section 7 -->
                                    <div x-show="activeSubForm === 'residualAlterPlan'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">Residual Beneficiary (Alternative Plan)</h1>                                            
                                        </div>
                                        <div class="mt-4">
                                            <p class="mb-2">If my multiple main beneficiaries do not survive me by thirty (30) days and my alternative plan comes into effect, I would like the following description to represent how I would like to distribute all of my possessions, with the exception of those items previously described.  This includes those items left to any beneficiaries who do not survive me by thirty (30) days, and for which there is no alternate  beneficiary who survives me by thirty (30) days.</p>
                                        </div>
                                        <div>
                                            <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of any beneficiaries you include in your description.</p>
                                        </div>
                                        <!-- 'residualAlterDetail' => ['residualDesc','residualBenefIndex'], -->
                                        <div class="mb-2">                                            
                                            <textarea class="h-40 p-2" x-model="formData.sec7.residualAlterDetail.residualDesc"></textarea>
                                        </div>
                                        <div>
                                            <p>If any of the beneficiaries identified above do not survive me by thirty (30) days, then I would like their share to go to the following alternate residual  beneficiary <span class="italic font-bold">(OPTIONAL):</span></p>
                                            <div x-init="updateState()" class="w-6/12">
                                                <label for="">Beneficiary:</label>
                                                <select x-model="formData.sec7.residualAlterDetail.residualBenefIndex" >
                                                    <template x-for="(benf, benefIndex) in formData.sec4.beneficiaryDetails">
                                                        <option :value="benefIndex" :selected="benefIndex == formData.sec7.residualAlterDetail.residualBenefIndex" x-text="benf.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <!-- Sub section "Second Level" Alternate of section 7 -->
                                    <div x-show="activeSubForm === 'secondLevelAlterBenef'">
                                        <div>
                                            <h1 class="text-4xl text-blue-500">"Second Level" Alternate</h1>                                            
                                        </div>
                                        <div class="mt-2">
                                            <p x-show="formData.sec7.alterBenefProvisions.radio==2">In the event that neither my multiple main beneficiaries nor my alternate beneficiary (<span x-text="formData.sec4.beneficiaryDetails[formData.sec7.alterBenefProvisions.everythingAlterBenefIndex].name"></span>) survive me by thirty (30) days, then: </p>
                                        </div>
                                        <div class="mt-2">
                                            <div class="flex flex-col">
                                                <!-- 'secondLevelAlter' => ['radio'=>1,'alterBenefIndex'=>0,'everythingDesc'=>''], -->
                                                <div class="mb-2">
                                                    <input type="radio" name="secondLevelAlterBen2" id="secondLevelAlterBen11" x-model="formData.sec7.secondLevelAlter.radio" value="1" class="mr-4"><label for="secondLevelAlterBen11">I would like everything that they would have received to go to the following "second level" alternate  beneficiary:</label>
                                                    <div>
                                                        <select x-model="formData.sec7.secondLevelAlter.alterBenefIndex" >
                                                            <template x-for="(benf, benefIndex) in formData.sec4.beneficiaryDetails">
                                                                <option :value="benefIndex" :selected="benefIndex == formData.sec7.secondLevelAlter.alterBenefIndex" x-text="benf.name"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="radio" name="secondLevelAlterBen2" id="secondLevelAlterBen22" x-model="formData.sec7.secondLevelAlter.radio" value="2" class="mr-4"><label for="secondLevelAlterBen22">Distribute everything that they would have received in the following way:</label>
                                                    <div>
                                                        <p class="italic text-red-600 font-semibold">Provide a very specific and detailed description. Also, you must include the full names and addresses of any beneficiaries you include in your description.</p>
                                                        <textarea class="h-1/5 p-2" x-model="formData.sec7.secondLevelAlter.everythingDesc"></textarea>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="radio" name="secondLevelAlterBen2" id="secondLevelAlterBen33" x-model="formData.sec7.secondLevelAlter.radio" value="3" class="mr-4"><label for="secondLevelAlterBen33">None of the above. I do not want to specify a "second level" alternate  beneficiary.</label>                                                    
                                                </div>
                                                <div class="mb-2">
                                                    <input type="radio" name="secondLevelAlterBen2" id="secondLevelAlterBen44" x-model="formData.sec7.secondLevelAlter.radio" value="4" class="mr-4"><label for="secondLevelAlterBen44">Undecided</label>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sub section Trusts for Young Beneficiaries of section 7 -->                                    
                                <div x-show="activeForm === 'sec8'">
                                    
                                    <div>
                                        <h1 class="text-4xl text-blue-500">Trusts for Young Beneficiaries</h1>                                            
                                    </div>
                                    <div class="mt-2">
                                        <p >The following people have been named as beneficiaries in your Will. If you wish, for each of these individuals you can specify whether the property you leave to them should be held "in trust".</p>
                                    </div>
                                    <div class="p-4 border-2">
                                        <!-- 'sec8' => [['trust'=>1,'expiryAge'=>-1,'shareType'=>1,'fraction'=>'','ageGranted'=>-1,'fractionRemainder'=>'','atThisAge'=>-1]] -->                                        
                                        <template x-for="(item,childIndex) in getYoungBenfificiaries()">                                            
                                            <div class="mt-10 flex">
                                                <div class="w-2/12 border-2">
                                                    <div class="min-h-16 border-b-8">
                                                        <p></p>
                                                    </div>
                                                    <div class="p-4">
                                                        <p x-text="childIndex+1"></p>
                                                    </div>
                                                </div>
                                                <div class="w-10/12 border-2 border-l-0">
                                                    <div class="min-h-16 border-b-8 p-4">
                                                        <p>Trust Details</p>
                                                    </div>
                                                    <div class="p-4">
                                                        <p class="mb-2"><strong x-text="formData.sec4.beneficiaryDetails[childIndex].name"></strong> (<strong x-text="formData.sec4.beneficiaryDetails[childIndex].relation"></strong>)</p>
                                                        <p class="mb-2">Trust:</p>
                                                        <div class="flex flex-col">                                                            
                                                            <div class="mb-2">
                                                                <input type="radio" :name="'trust1'+childIndex" :id="'trust11'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].trust" value="1" class="mr-4"><label :for="'trust11'+childIndex">Do not create a trust for this beneficiary</label>                                                                
                                                            </div>
                                                            <div class="mb-2">                                                                
                                                                <input type="radio" :name="'trust1'+childIndex" :id="'trust12'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].trust" value="2" class="mr-4"><label :for="'trust12'+childIndex">Create a trust for this beneficiary, as follows:</label>
                                                            </div>
                                                            <div x-show="formData.sec8.youngBenefs[childIndex].trust==2" class="mb-2 bg-slate-50 ml-auto w-10/12 p-4">            
                                                                <div class="mb-2">
                                                                    <label class="font-bold" :for="'trust-age-expite-select'+childIndex">Age at which trust expires</label>
                                                                    <select class="w-4/12 block" :id="'trust-age-expite-select'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].expiryAge">
                                                                        <template x-for="(item,index) in getExpiryAges()">                                                                        
                                                                            <option :value="item" :selected="index+18 == formData.sec8.youngBenefs[childIndex].expiryAge" x-text="item"></option>
                                                                        </template>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2 w-8/12">
                                                                    <div class="flex flex-col">                                                            
                                                                        <div class="mb-2">
                                                                            <input type="radio" :name="'shareType1'+childIndex" :id="'shareType11'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].shareType" value="1" class="mr-4"><label :for="'shareType11'+childIndex">Hold entire share until trust expires</label>                                                                
                                                                        </div>
                                                                        <div class="mb-2">                                                                
                                                                            <input type="radio" :name="'shareType1'+childIndex" :id="'shareType12'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].shareType" value="2" class="mr-4"><label :for="'shareType12'+childIndex">Release portions of the current value of the trust before the trust expires:</label>
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <p class="mb-2">(use fractions such as 1/2 or 1/3)</p>
                                                                            <div class="mb-2">
                                                                                <label :for="'frac_'+childIndex" class="fint-bold">Fraction:</label>
                                                                                <input type="text" :id="'frac_'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].fraction">
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <label class="font-bold" :for="'grantedAge-select'+childIndex">Age at which trust expires</label>
                                                                                <select :id="'grantedAge-select'+childIndex" x-model="formData.sec8.youngBenefs[childIndex].ageGranted">
                                                                                    <template x-for="(item,index) in getExpiryAges()">                                                                        
                                                                                        <option :value="item" :selected="index+18 == formData.sec8.youngBenefs[childIndex].ageGranted" x-text="item"></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>
                                        </template>                                        
                                    </div>
                                    
                                </div>
                                <div x-show="activeForm === 'sec9'">
                                    <div class="mb-2">
                                        <h1 class="text-4xl text-blue-500">Forgive Debts</h1>                                            
                                    </div>
                                    <div class="mb-2">
                                        <p class="mb-2">Is there anything specific owed to you that you wish to forgive or cancel at the time of your death?</p>
                                        <p class="mb-2">If you have debts owed to you that you do not wish to forgive, they become part of your estate. After you complete your Will, you should include these in the "Personal Details & Assets" form located on the MyWill™ Main Menu.</p>
                                    </div>
                                    <div class="mb-2">
                                        <!-- 'sec9' => ['forgive','forgiveDetails'], -->
                                        <div class="flex flex-col">                                                            
                                        <div class="mb-2">
                                            <input type="radio" name="forgive1" id="forgive11" x-model="formData.sec9.forgive" value="1" class="mr-4"><label for="forgive11">No</label>                                                                
                                        </div>
                                        <div class="mb-2">                                                                
                                            <input type="radio" name="forgive1" id="forgive12" x-model="formData.sec9.forgive" value="2" class="mr-4"><label for="forgive12">Yes, and the details are as follows:</label>
                                        </div>
                                        <div x-show="formData.sec9.forgive==2">
                                            <label for="forgiveDetails">Description</label>
                                            <textarea class="h-60 p-2 w-6/12 block" id="forgiveDetails" x-model="formData.sec9.forgiveDetails" placeholder="Enter detailed description"></textarea>
                                        </div>
                                        <div class="mb-2">                                                                
                                            <input type="radio" name="forgive1" id="forgive13" x-model="formData.sec9.forgive" value="3" class="mr-4"><label for="forgive13">Undecided</label>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div x-show="activeForm === 'sec10'">
                                    <div class="mb-2">
                                        <h1 class="text-4xl text-blue-500">Attachments</h1>                                            
                                    </div>
                                    <div class="mb-2">
                                        <p class="mb-2">Check this box if you plan to store additional information with your Will, such as a document or letter providing additional instructions or other information.</p>                                        
                                    </div>
                                    <div class="mb-2 bg-slate-50 p-8 shadow">
                                        <div>
                                            <input class="mr-4" :checked="formData.sec10.attachement=='true'" x-model="formData.sec10.attachement" type="checkbox" id="attachements"><label for="attachements">I am going to attach an instruction to my Will</label>
                                        </div>
                                        <div><p>If you check this box, a clause will be inserted into your Will that references the existence of the document.</p></div>
                                    </div>
                                    <div class="mb-2 bg-slate-50 p-8 shadow">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center gap-2 mt-4">
                    <button class="uppercase bg-gray-300 px-4 py-2 rounded" @click="backPage">Back</button>
                    <button class="uppercase bg-gray-300 px-4 py-2 rounded" @click="save">Save/Exit</button>
                    <button class="uppercase bg-gray-300 px-4 py-2 rounded" @click="nextPage">Next</button>
                </div>
            </div>

        </div>
    </div>    
    <script>        
        var canCreate = true
        function updateNumViews() {
            url = "<?= admin_url('admin-ajax.php'); ?>"
            jQuery.ajax({
                type : "post",                        
                url : url,
                data : {action: "updateNumViews"},
                success: function(response) {
                    if(response.success) {
                        canCreate = true
                    }
                    else if(response.success==false) {
                        canCreate = false
                        alert("Cannot create or modify will more than 5 times.");                         
                    }
                }
            })
        }
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                child: 0,
                activeForm: 'home',
                progressValue:1,
                activeSubForm: 'intro',
                checkSubSec: ['sec3','sec6','sec7'],
                allPages: [
                    'sec1',
                    'sec2',
                    'sec3',
                    'sec4',
                    'sec5',
                    'sec6',
                    'sec7',
                    'sec8',
                    'sec9',
                    'sec10',
                ],
                allSubPages: {
                    'sec3': ['intro', 'partner', 'children', 'grandChildren', 'deceased'],
                    'sec6': ['intro','alterExecutor'],
                    'sec7': ['intro','charities','petTrusts','possessionsDist','makeBequests','divideEqual','multiBenefProvisions','alterBenefIntro','alterBenef','makeAlterPlan','makeAlterPlanBequests','describeAlterPlan','descSpecificBequests','residualAlterPlan','secondLevelAlterBenef'],
                },
                mainForm:false,
                selectedOpt: 1,
                beneficiaryNames: [],
                formData : willsFormData,

                qna: {
                    home:{},createMod:{},
                    sec1: {
                        'What if I have a question that isn\'t answered anywhere on the page?': 'No problem. Simply click on the \"CONTACT US\" link that appears in the menu at the top of each page. We\'d be happy to answer any questions that you have.\nBe sure to save your work by clicking the \"SAVE/EXIT\" button and creating an account, so that you can come back later to continue from where you left off.',
                        'How do I get started?': 'It\'s easy! Just click the \"NEXT\" button at the bottom of the page.',                        
                    },
                    sec2: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec3: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec4: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec5: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec6: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec7: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec8: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec9: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    sec10: {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                },

                sectionSelOption: [
                    'Section 1: Introduction',
                    'Section 2: Personal Details',
                    'Section 3: Family Status',
                    'Section 4: Other Beneficiaries',
                    'Section 5: Guardians for Minor Children',
                    'Section 6: Executor',
                    'Section 7: Distribute your Possessions',
                    'Section 8: Trust for Young Beneficiaries',
                    'Section 9: Forgive Debts',
                    'Section 10: Next Step',
                ],

                validateRules: {
                    sec2: {
                        prefix: {
                            max: 20,
                            min: 2,
                            required: true,
                        },
                        suffix: {
                            max: 20,
                            required: false,
                        },
                        firstName: {
                            max: 20,
                            min: 2,
                            required: true,
                        },
                        middleName: {
                            max: 20,
                            required: false,
                        },
                        lastName: {
                            max: 20,
                            min: 2,
                            required: true,
                        },
                        email: {
                            max: 50,
                            min: 2,
                            email: true,
                            required: true,
                        },
                        gender: {
                            required: true,
                        },
                        country: {
                            required: true,
                        },
                        state: {
                            required: true,
                        },
                        city: {
                            max: 20,
                            min: 2,
                            required: true,
                        },
                    },
                    sec3: {
                        intro: {
                            status: {
                                required: true,
                            },
                            children: {
                                required: true,
                            },
                            grandChildren: {
                                required: true,
                            },
                        },

                        partner: {
                            fullName: {
                                required: true,
                                max: 50,
                                min: 2,
                            },
                            relation: {
                                required: true,
                            },
                            partnerGender: {
                                required: true,
                            }
                        },
                        children: {

                        },
                        grandChildren: {

                        },
                        deceased: {
                            
                        },
                    },
                    sec6: {
                        intro: {

                        },
                        alterExecutor: {

                        },
                    },
                    sec7: {
                        intro: {

                        },
                        charities: {

                        },
                        petTrusts: {

                        },
                        possessionsDist:{

                        },
                        divideEqual:{

                        },
                        makeBequests:{

                        },
                        multiBenefProvisions: {

                        },
                        alterBenef:{

                        },
                        youngBenef:{

                        },
                        secondLevelAlterBenef:{

                        }
                    }
                },
                getExpiryAges(){
                    let ages = []
                    for(let index=0;index<17;index++){
                        if(index==0) ages.push('(Select Age)')
                        else ages.push(index+18)
                    }
                    return ages
                },
                addChild(e){
                    this.formData.sec3.numChildren++
                    this.formData.sec3.childDetails.push({'name':'','relation':'','dob':''})
                },
                removeChild(index) {
                    this.formData.sec3.childDetails.splice(index, 1);
                    this.formData.sec3.numChildren--
                },
                addGrandChild(e){
                    this.formData.sec3.numGrandChildren++
                    this.formData.sec3.grandChildDetails.push({'name':'','relation':'','dob':''})
                },
                removeGrandChild(index) {
                    this.formData.sec3.grandChildDetails.splice(index, 1);
                    this.formData.sec3.numGrandChildren--
                },
                addDeceased(e){
                    this.formData.sec3.numDeceased++
                    this.formData.sec3.deceasedDetails.push({'name':'','gender':'','relation':''})
                },
                removeDeceased(index) {
                    this.formData.sec3.deceasedDetails.splice(index, 1);
                    this.formData.sec3.numDeceased--
                },
                addBeneficiary(e){
                    this.formData.sec4.numBeneficiary++
                    this.formData.sec4.beneficiaryDetails.push({'gender':'','name':'','relation':'','address':''})
                },
                removeBeneficiary(index) {
                    this.formData.sec4.beneficiaryDetails.splice(index, 1);
                    this.formData.sec4.numBeneficiary--
                },
                addExecutor(e){
                    this.formData.sec6.numExecutor++
                    this.formData.sec6.executorDetails.push({'name':'','relation':'','address':''})
                },
                removeExecutor(index) {
                    this.formData.sec6.executorDetails.splice(index, 1);
                    this.formData.sec6.numExecutor--
                },
                addAlterExecutor(e){
                    this.formData.sec6.numAlterExecutor++
                    this.formData.sec6.alterExecutorDetails.push({'name':'','relation':'','address':''})
                },
                removeAlterExecutor(index) {
                    this.formData.sec6.alterExecutorDetails.splice(index, 1);
                    this.formData.sec6.numAlterExecutor--
                },
                addBequest(e){
                    this.formData.sec7.numBequests++
                    this.formData.sec7.bequestDetails.push({"type":1,"amount":"","percentage":"","asset":"","charityName":""})
                },
                removeBequest(index) {
                    this.formData.sec7.bequestDetails.splice(index, 1);
                    this.formData.sec7.numBequests--
                },                
                addPet(e){
                    this.formData.sec7.numPets++
                    this.formData.sec7.petDetails.push({"name":"","type":"","amount":"","caretaker":"","alterCaretaker":""})
                },
                removePet(index) {
                    this.formData.sec7.petDetails.splice(index, 1);
                    this.formData.sec7.numPets--
                },
                addSpecific(e){
                    this.formData.sec7.numSpecifics++
                    this.formData.sec7.specificsDetails.push({"type":1,"gift":"","description":"","giftBenefIndex":0,"alterGiftBenefIndex":0})
                },
                removeSpecific(index) {
                    this.formData.sec7.specificsDetails.splice(index, 1);
                    this.formData.sec7.numSpecifics--
                },
                addAlterSpecific(e){
                    this.formData.sec7.numAlterSpecifics++
                    this.formData.sec7.alterSpecificsDetails.push({"type":1,"gift":"","description":"","giftBenefIndex":0,"alterGiftBenefIndex":0})
                },
                removeAlterSpecific(index) {
                    this.formData.sec7.alterSpecificsDetails.splice(index, 1);
                    this.formData.sec7.numAlterSpecifics--
                },
                getNumFormat(number){
                    switch (number % 10) {
                        case 1:
                            return number + 'st';
                        case 2:
                            return number + 'nd';
                        case 3:
                            return number + 'rd';
                        default:
                            return number + 'th';
                    }
                },
                getBenefNameByIndex(index){
                    try{
                        return this.formData.sec4.beneficiaryDetails[index].name
                    }catch (err){
                        return 'None Selected'
                    }
                },
                getNumExecutors(){
                    return this.formData.sec6.executorDetails.length
                },
                getExecutorNames(){
                    let names = ''
                    if(this.formData.sec6.executorDetails.length === 1) return this.formData.sec6.executorDetails[0].name
                    for(let i=0;i<this.formData.sec6.executorDetails.length;i++){
                        if(i!==this.formData.sec6.executorDetails.length-1)
                        names += this.formData.sec6.executorDetails[i].name+', '
                        else if(i===this.formData.sec6.executorDetails.length-1)
                        names += 'and ' + this.formData.sec6.executorDetails[i].name
                        else
                        names += this.formData.sec6.executorDetails[i].name
                    }
                    return names
                },
                getAlterBenefs(discardIndex){
                    let benefs = this.formData.sec4.beneficiaryDetails
                    let alterBenefs = []
                    benefs.forEach((item,index)=>{
                        if(index!==discardIndex) alterBenefs.push({'value':index,'name':item.name})
                    })
                    alterBenefs.unshift({'value':-1,'name':'[make selection]'})
                    return alterBenefs
                },
                isYoungInfoRequrired(){
                    let temp = [1,2,3,4].includes(+(this.formData.sec7.possessionDist))
                    let temp2 = [1].includes(+(this.formData.sec7.alterBenefProvisions.radio))
                    if(temp && temp2)
                        return true
                    return false
                },
                getYoungBenfificiaries(){
                    let benefsDetails = this.formData.sec4.beneficiaryDetails
                    let length = this.formData.sec8.youngBenefs.length
                    
                    if(length<benefsDetails.length){
                        let youngBenefs = this.formData.sec8.youngBenefs
                        for(let i=0; i<benefsDetails.length-length;i++){
                            youngBenefs.push({"trust":1,"expiryAge":-1,"shareType":1,"fraction":"","ageGranted":-1,"fractionRemainder":"","atThisAge":-1})
                        }
                        this.formData.sec8.youngBenefs = youngBenefs
                        return youngBenefs
                    }else {
                        
                        return this.formData.sec8.youngBenefs
                    }
                },
                validateError: {},
                
                validate(inputName) {
                    let rules;
                    if (this.checkSubSec.includes(this.activeForm)) {
                        rules = this.validateRules[this.activeForm][this.activeSubForm][inputName];
                        // 

                    } else {
                        rules = this.validateRules[this.activeForm][inputName];
                    }
                    // 
                    const value = this.formData[this.activeForm][inputName].trim();

                    this.validateError[inputName] = '';

                    if (rules.required && !value) {
                        this.validateError[inputName] = 'This field is required.';
                        return;
                    }
                    if (rules.email && !this.isValidEmail(value)) {
                        this.validateError[inputName] = 'Invalid email address.';
                        return;
                    }
                    if (rules.max && value.length > rules.max) {
                        this.validateError[inputName] = `Maximum ${rules.max} characters allowed.`;
                        return;
                    }

                    if (rules.min && value.length < rules.min) {
                        this.validateError[inputName] = `Minimum ${rules.min} characters required.`;
                        return;
                    }
                },

                validateOnSubmit() {
                    if (this.checkSubSec.includes(this.activeForm)) {
                        for (const inputName in this.validateRules[this.activeForm][this.activeSubForm]) {                        
                            this.validate(inputName);
                        }
                    } else {
                        for (const inputName in this.validateRules[this.activeForm]) {
                            this.validate(inputName);
                        }
                    }
                    for (const inputName in this.validateError) {
                        if (this.validateError[inputName]) {
                            return false;
                        }
                    }
                    return true;
                },

                isValidEmail(email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                },

                async openPage(pageName) {
                    switch (pageName) {
                        case 'createMod':
                            // await updateNumViews()
                            // console.log(canCreate)
                            if(canCreate){
                                this.activeForm = 'createMod';
                            } else {
                                this.activeForm = 'home';
                            }
                            break;                        
                        case 'view':
                            window.location = 'view-will'
                        default:
                            this.activeForm = 'home';                            
                            break;
                    }
                    if(pageName.includes('sec')){
                        this.activeForm = pageName;
                        this.mainForm = true;
                        this.updateState()
                    }
                },                
                selectChanged(e) {
                    if (this.validateOnSubmit()) {                        
                        let page = e.target.value;
                        this.activeForm = page
                        if(this.checkSubSec.includes(page)) this.activeSubForm = 'intro'
                        this.updateState()
                    }else{
                        this.selectedOpt = this.activeForm
                    }
                },

                backPage() {
                    let page = this.allPages.indexOf(this.activeForm);

                    if (page > 0) {
                        if(this.checkSubSec.includes(this.activeForm)){
                            let activeSubIndex = this.allSubPages[this.activeForm].indexOf(this.activeSubForm)
                            if(activeSubIndex>0){
                                let subPage = this.validPrevSubPage(activeSubIndex)                                
                                if(subPage) {
                                    this.activeSubForm = subPage
                                }
                                else{
                                    let prevPage = page - 1;
                                    this.activeForm = this.allPages[prevPage];    
                                }
                            }else{
                                let prevPage = page - 1;
                                this.activeForm = this.allPages[prevPage];
                            }                            
                        }else{
                            let prevPage = page - 1;
                            this.activeForm = this.allPages[prevPage];
                        }
                    }
                    this.updateState()
                },
                validPrevSubPage(activeSubIndex){
                    if(activeSubIndex>0){                                    
                        let prevSubForm = this.allSubPages[this.activeForm][activeSubIndex-1]                                               
                        if(this.selectedFields[this.activeForm][prevSubForm] || this.selectedFields[this.activeForm][prevSubForm]==undefined){
                            return prevSubForm
                        }
                        else {
                            return this.validPrevSubPage(activeSubIndex-1)
                        }
                    }
                    else{
                        return false
                    }
                },
                nextPage() {
                    if (this.validateOnSubmit()) {
                        let page = this.allPages.indexOf(this.activeForm);
                        if (page >= 0 && page < this.allPages.length - 1) {
                            this.submit(page);
                        }
                    }
                    this.updateState()
                },
                selectedFields: {
                    sec3: {
                        partner: false,
                        children: false,
                        grandChildren: false,
                        deceased: true
                    },
                    sec6: {
                        alterExecutor: true,
                    },
                    sec7: {
                        charities: true,
                        petTrusts: true,
                        possessionsDist: true,
                        divideEqual: false,
                        makeBequests: false,
                        multiBenefProvisions: false,
                        alterBenefIntro: false,
                        alterBenef: false,                        
                        secondLevelAlterBenef: false,
                        makeAlterPlan: false,
                        makeAlterPlanBequests: false,
                        describeAlterPlan: false,
                        descSpecificBequests: false,
                        residualAlterPlan: false,
                    },

                },                
                submit(page) {
                    if (this.checkSubSec.includes(this.activeForm)) {
                        if (this.activeForm === "sec3") {
                            if (this.activeSubForm === 'intro') {
                                this.selectedFields[this.activeForm].partner = [2, 3, 4, 5, 7].includes(+(this.formData[this.activeForm].status));
                                this.selectedFields[this.activeForm].children = this.formData[this.activeForm].children === '1';
                                this.selectedFields[this.activeForm].grandChildren = this.formData[this.activeForm].grandChildren === '1';                                
                            }                             
                        }
                        if(this.checkSubSec.includes(this.activeForm)){
                            if(this.activeForm === "sec7") {
                                if(this.activeSubForm == 'possessionsDist'){
                                    this.selectedFields[this.activeForm].divideEqual = [1,2].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].makeBequests = [2,4,5].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].multiBenefProvisions = [1,2].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].alterBenefIntro = [2,4].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].alterBenef = [1,2,3,4].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].residualAlterPlan = [1,2,3,4,5,6].includes(+(this.formData[this.activeForm].possessionDist))
                                    this.selectedFields[this.activeForm].descSpecificBequests = [6].includes(+(this.formData[this.activeForm].possessionDist))
                                    // descSpecificBequests
                                    this.selectedFields[this.activeForm].secondLevelAlterBenef = [1,2,3,4].includes(+(this.formData[this.activeForm].possessionDist))
                                }
                                if(this.activeSubForm == 'alterBenef'){                                    
                                    this.selectedFields[this.activeForm].secondLevelAlterBenef = [2,3].includes(+(this.formData[this.activeForm].alterBenefProvisions.radio))
                                    this.selectedFields[this.activeForm].makeAlterPlan = [3,4].includes(+(this.formData[this.activeForm].alterBenefProvisions.radio))
                                    this.selectedFields[this.activeForm].makeAlterPlanBequests = [3,4].includes(+(this.formData[this.activeForm].alterBenefProvisions.radio))
                                    this.selectedFields[this.activeForm].residualAlterPlan = [4,5].includes(+(this.formData[this.activeForm].alterBenefProvisions.radio))
                                    this.selectedFields[this.activeForm].describeAlterPlan = [5].includes(+(this.formData[this.activeForm].alterBenefProvisions.radio))
                                }
                                if(this.selectedFields[this.activeForm].divideEqual){  //Update section 4 field
                                    this.formData.sec4.otherBeneficiaries = 2
                                }                                
                            }
                        }
                        let index = this.allSubPages[this.activeForm].indexOf(this.activeSubForm);
                        if(index<this.allSubPages[this.activeForm].length-1){
                            let nextSub = this.validNextSubPage(index)
                            if(nextSub){
                                this.activeSubForm = nextSub
                            }
                            else{
                                this._nextPage(page)
                            }
                        } else {
                            this._nextPage(page)
                        }
                    } else {
                        this._nextPage(page)
                    }
                },
                validNextSubPage(activeSubIndex){
                    if(activeSubIndex < this.allSubPages[this.activeForm].length-1){ 
                        let activeSubForm = this.allSubPages[this.activeForm][activeSubIndex]
                        let nextSubForm = this.allSubPages[this.activeForm][activeSubIndex+1]                        
                        if(this.selectedFields[this.activeForm][nextSubForm]){
                            return nextSubForm
                        }
                        else {
                            return this.validNextSubPage(activeSubIndex+1)
                        }
                    }
                    else{
                        return false
                    }                    
                },
                _nextPage(page) {
                    let nextPage = page + 1;
                    this.activeForm = this.allPages[nextPage];
                    if (this.checkSubSec.includes(this.activeForm)) {
                        this.activeSubForm = this.allSubPages[this.activeForm][0];
                    }
                    this.updateState()
                },

                async save() {
                    if (this.validateOnSubmit()) {
                        data = JSON.parse(JSON.stringify(this.formData))
                        jQuery.post('<?= admin_url('admin-ajax.php') ?>',{
                            action:'update_form_data',
                            data:data
                        },
                        function(response){
                            console.log(response)
                        })
                        alert('Save Successfully!');
                    }
                },
                isMainForm(){                    
                    return this.allPages.includes(this.activeForm)
                },
                updateState(){
                    // Update Progress Bar
                    setTimeout(()=>{
                        try {
                            if (this.isMainForm()) {
                                this.progressValue = this.activeForm.split("sec")[1]                                               
                            }
                        }
                        catch (err) {
                            console.log('Progress bar cannot be updated');
                        }                        
                    },0)
                    this.selectedOpt = this.activeForm // Update Select Section
                    // Check if it is a main form
                    if(this.isMainForm()) {
                        this.mainForm = true
                    }else{
                        this.mainForm = false
                    }

                    // Check if WIll holder is having any living minor child/grandchild for section 5
                    if(this.activeForm=='sec5'){                        
                        if(this.hasMinorChild()) {                            
                        }
                        if(this.formData.sec5.guardianDetails.length < this.minorChildren.length) this.extendGuardianFields(this.formData.sec5.guardianDetails.length)
                    } else if(this.activeForm=='sec7' && this.activeSubForm=='possessionsDist'){
                        this.beneficiaryNames = this.formData.sec4.beneficiaryDetails.map((item,index)=>{
                            return {'name':item.name,'value':index}
                        })
                        this.beneficiaryNames.unshift({'name':'None','value':-1})
                        
                    }
                    if(this.activeForm=='sec7' && this.activeSubForm=='multiBenefProvisions'){
                        this.extendMultiBenefProvisions()
                    }
                },
                extendGuardianFields(lengthDetails){
                    if(lengthDetails<this.minorChildren.length){
                        let dif = this.minorChildren.length - lengthDetails
                        for(let i=0;i<dif;i++)
                        this.formData.sec5.guardianDetails.push({'childName':'','name':'','reason':'','alterName':''})                    
                    }
                },
                extendMultiBenefProvisions(){
                    let lengthBenef = this.formData.sec4.beneficiaryDetails.length
                    let dif = lengthBenef - this.formData.sec7.multiBenefProvisions.length
                    if(lengthBenef>dif){
                        for(let i=0;i<dif;i++){
                            this.formData.sec7.multiBenefProvisions.push({"radio":1,"alterBenefIndex":-1,"shareDesc":""})
                        }
                    }
                },
                hasMinorChild(){
                    let children = this.formData.sec3.children
                    if(children==1){
                        let allChild = this.formData.sec3.childDetails
                        let dobs =allChild.map((detail)=>detail.dob)
                        let minorChildNames = []                        
                        for(let i=0;i<dobs.length;i++){                            
                            dob = new Date(dobs[i])
                            var month_diff = Date.now() - dob.getTime();
                            var age_dt = new Date(month_diff);
                            var year = age_dt.getUTCFullYear(); 
                            var age = Math.abs(year - 1970);
                            if(age<18) {        
                                minorChildNames.push(this.formData.sec3.childDetails[i].name)                                                        
                            }
                        }
                        if(minorChildNames.length>0) {
                            this.minorChildren = minorChildNames
                            return true
                        }
                    }
                    else return false
                },
                minorChildren: [],
                partner: {},
                children: [],
                grandChildren: [],
            }))
        })
    </script>
<?php
} else {
    echo '<h2>This content is restricted</h2>';
}
get_footer();
?>