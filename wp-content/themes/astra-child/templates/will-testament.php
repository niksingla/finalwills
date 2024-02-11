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
    HTML;

    echo $html;
});

get_header();

if (is_user_logged_in()) {
    $userData = get_userdata(get_current_user_id());
    $userMeta = get_user_meta(get_current_user_id());
    $fullName = sprintf("%s %s", $userMeta['first_name'][0], $userMeta['last_name'][0]);
?>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .ast-container {
            margin: 0;
            padding: 0;
            width: 100%;
        }
    </style>
    <!-- Code here -->
    <div x-data="data">
        <div x-cloak>
            <div>
                <a href="javascript:void(0)">
                    <button class="bg-green-600 text-white rounded border text-2xl px-8 py-2 hover:bg-green-500">Back</button>
                </a>

                <div class="border-b-2 border-red-700 py-4">
                    <h1 class="text-4xl text-blue-500">MyWill™ - Main Menu For <?= $fullName ?></h1>
                </div>

            </div>
            <div x-show="activeForm === 'home'">
                <div class="w-screen mx-auto p-10">

                    <div class="w-10/12 mx-auto">

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
                <div class="w-screen mx-auto p-10">
                    <p>
                        Below is a list of the sections in the MyWill™ question-and-answer wizard.</p>
                    <br>

                    <p>If you have not yet started to answer the questions, or you'd like to review and possibly make changes to your answers, or even if you're not sure, click on "Start Here". Otherwise, you may click on any section to continue from that point or to make modifications by jumping to a particular section.
                    </p>

                    <p class="font-bold text-blue-400 hover:text-blue-500 my-8"><a href="">Return to the MyWill™ main menu</a></p>

                    <div>
                        <ul>

                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max" @click="openPage('sec-1')">
                                Section 1: Introduction <span class="text-red-600">START HERE</span>
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 2: Personal Details
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 3: Family Status
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 4: Other Beneficiaries
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 5: Guardians for Minor Children
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 6: Executor
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 7: Distribute Your Possessions
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 8: Trusts for Young Beneficiaries
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
                                Section 9: Forgive Debts
                            </li>
                            <li class="text-blue-400 hover:text-blue-500 cursor-pointer w-max">
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
                            <progress value="70" max="100"></progress>
                            <div>Section 1 of 10</div>
                        </div>
                    </div>
                    <div>
                        <select @change="selectChanged($event)">
                            <template x-for="(item, index) in sectionSelOption">
                                <option :value="index" :selected="selectedOpt == index ? true : false" x-text="item"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="mt-10">
                    <div class="w-full">
                        <div class="flex flex-row justify-center gap-2">
                            <div class="flex-initial w-6/12 bg-blue-600 text-white px-10 py-4">
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
                            <div x-show="activeForm === 'sec-1'">
                                <p>Introduction</p>
                                <i class="font-bold">We've made this easy! This should only take a short amount of your time...</i>
                                <p>You will be asked a series of questions to help you create your Last Will and Testament.</p>

                                <p>While answering the questions, if you need general assistance on the section, just read the Common Questions which appear on every page. If you don't see the questions, simply click on the big near the top of the page.</p>

                                <p>Specific help for parts of a page that may be unclear is available by tapping (or moving your mouse over) the small symbol which appears next to some questions.</p>

                                <p>At any point you can save your work and return later.</p>

                                <p>When you are done, you should print and sign your document in the presence of witnesses to make it a legal Will.</p>

                                <p>To begin stepping through these questions, click on the "NEXT" button which appears below...</p>
                            </div>
                            <div x-show="activeForm === 'sec-2'">
                                <p>Personal Details</p>
                                <p>
                                    It is important that you provide the information below so that the MyWill™ wizard can format a document that is custom-made based on your name, gender and local jurisdiction.
                                </p>

                                <p>* = required information</p>
                                <div>
                                    <div>
                                        <p></p>
                                        <div>

                                        </div>
                                        <div class="horizontalform">
                                            <div>
                                                <label for="ctl00_ctl00_MainContent_WizardContent_txtPrefix">Prefix (eg. Mr., Ms., Dr.)<span class="redtext">*</span></label>
                                                <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtPrefix" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_txtFirstName">First Name<span class="redtext">*</span></label>
                                                    <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtFirstName" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_txtMiddleName">Middle Name</label>
                                                    <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtMiddleName" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_txtLastName">Last Name/Surname<span class="redtext">*</span></label>
                                                    <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtLastName" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_txtSuffix">Suffix (eg. Jr., Sr.)</label>
                                                    <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtSuffix" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_drpCountry">Country<span class="redtext">*</span></label>
                                                    <select id="ctl00_ctl00_MainContent_WizardContent_drpCountry" class="select">
                                                        <option value=""></option>
                                                        <option value="Canada">Canada</option>
                                                        <option value="South Africa">South Africa</option>
                                                        <option value="United Kingdom - England">United Kingdom - England</option>
                                                        <option value="United Kingdom - Wales">United Kingdom - Wales</option>
                                                        <option value="United States" selected="selected">United States</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div>
                                                    <label for="ctl00_ctl00_MainContent_WizardContent_drpStateProvince">State / Province / County</label>
                                                    <select id="ctl00_ctl00_MainContent_WizardContent_drpStateProvince" class="select">
                                                        <option value="[make selection]">[make selection]</option>
                                                        <option value="Alabama">Alabama</option>
                                                        <option value="Alaska">Alaska</option>
                                                        <option value="Arizona">Arizona</option>
                                                        <option value="Arkansas">Arkansas</option>
                                                        <option value="California" selected="selected">California</option>
                                                        <option value="Colorado">Colorado</option>
                                                        <option value="Connecticut">Connecticut</option>
                                                        <option value="Delaware">Delaware</option>
                                                        <option value="District of Columbia">District of Columbia</option>
                                                        <option value="Florida">Florida</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Hawaii">Hawaii</option>
                                                        <option value="Idaho">Idaho</option>
                                                        <option value="Illinois">Illinois</option>
                                                        <option value="Indiana">Indiana</option>
                                                        <option value="Iowa">Iowa</option>
                                                        <option value="Kansas">Kansas</option>
                                                        <option value="Kentucky">Kentucky</option>
                                                        <option value="Louisiana">Louisiana</option>
                                                        <option value="Maine">Maine</option>
                                                        <option value="Maryland">Maryland</option>
                                                        <option value="Massachusetts">Massachusetts</option>
                                                        <option value="Michigan">Michigan</option>
                                                        <option value="Minnesota">Minnesota</option>
                                                        <option value="Mississippi">Mississippi</option>
                                                        <option value="Missouri">Missouri</option>
                                                        <option value="Montana">Montana</option>
                                                        <option value="Nebraska">Nebraska</option>
                                                        <option value="Nevada">Nevada</option>
                                                        <option value="New Hampshire">New Hampshire</option>
                                                        <option value="New Jersey">New Jersey</option>
                                                        <option value="New Mexico">New Mexico</option>
                                                        <option value="New York">New York</option>
                                                        <option value="North Carolina">North Carolina</option>
                                                        <option value="North Dakota">North Dakota</option>
                                                        <option value="Ohio">Ohio</option>
                                                        <option value="Oklahoma">Oklahoma</option>
                                                        <option value="Oregon">Oregon</option>
                                                        <option value="Pennsylvania">Pennsylvania</option>
                                                        <option value="Rhode Island">Rhode Island</option>
                                                        <option value="South Carolina">South Carolina</option>
                                                        <option value="South Dakota">South Dakota</option>
                                                        <option value="Tennessee">Tennessee</option>
                                                        <option value="Texas">Texas</option>
                                                        <option value="Utah">Utah</option>
                                                        <option value="Vermont">Vermont</option>
                                                        <option value="Virginia">Virginia</option>
                                                        <option value="Washington">Washington</option>
                                                        <option value="Washington D.C.">Washington D.C.</option>
                                                        <option value="West Virginia">West Virginia</option>
                                                        <option value="Wisconsin">Wisconsin</option>
                                                        <option value="Wyoming">Wyoming</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>
                                                <label for="ctl00_ctl00_MainContent_WizardContent_txtCity">City / Town<span class="redtext">*</span></label>
                                                <input type="text" value="" id="ctl00_ctl00_MainContent_WizardContent_txtCity" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>
                                                <label for="ctl00_ctl00_MainContent_WizardContent_txtEmailAddress">Email Address<span class="redtext">*</span></label>
                                                <input type="email" value="" id="ctl00_ctl00_MainContent_WizardContent_txtEmailAddress" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div>
                                                <p class="form-label mt-2">Gender pronoun:<span class="redtext">*</span></p>
                                                <div class="flex flex-col">
                                                    <div>
                                                        <input type="radio" name="gender" class="mr-4"><label for="">Male (he/his)</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="gender" class="mr-4"><label for="">Female (she/her)</label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" name="gender" class="mr-4"><label for="">Neutral (they/their)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="activeForm === 'sec-3'">
                                <div>
                                    <p>Family Status</p>
                                    <p>* = required information</p>
                                    <div>
                                        <label for="">Marital Status</label>
                                        <select name="" id="">
                                            <option value="">[make selection]</option>
                                            <option value="">single</option>
                                            <option value="">married</option>
                                            <option value="">separated</option>
                                            <option value="">separated, but want my spouse to be the main beneficiary</option>
                                            <option value="">divorced</option>
                                            <option value="">widowed</option>
                                            <option value="">in a civil union / domestic partnership</option>
                                        </select>
                                    </div>

                                    <div class="flex flex-col">
                                        <p>Living Children:</p>

                                        <div>
                                            <input type="radio" name="children" class="mr-4"><label for="">Yes</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="children" class="mr-4"><label for="">No</label>
                                        </div>
                                    </div>

                                    <div class="flex flex-col">
                                        <p>Living Grand-Children:</p>

                                        <div>
                                            <input type="radio" name="grandChildren" class="mr-4"><label for="">Yes</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="grandChildren" class="mr-4"><label for="">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <p>Spouse/Partner Details</p>
                                    <p>* = required information</p>

                                    <div>
                                        <label for="">Full Name</label>
                                        <input type="text" name="" id="">
                                    </div>

                                    <div>
                                        <label for="">Relation</label>
                                        <select name="" id="">
                                            <option value="">[make selection]</option>
                                            <option value="">wife</option>
                                            <option value="">husband</option>
                                            <option value="">common law wife</option>
                                            <option value="">common law husband</option>
                                            <option value="">partner</option>
                                        </select>
                                    </div>

                                    <div>
                                        <p class="form-label mt-2">Gender pronoun:<span class="redtext">*</span></p>
                                        <div class="flex flex-col">
                                            <div>
                                                <input type="radio" name="gender" class="mr-4"><label for="">Male (he/his)</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="gender" class="mr-4"><label for="">Female (she/her)</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="gender" class="mr-4"><label for="">Neutral (they/their)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Children -->
                                <div>
                                    <p>Identify Children</p>
                                    <p>* = required information</p>

                                    <div>
                                        <label for="">Child's Full Name</label>
                                        <input type="text">
                                    </div>

                                    <div>
                                        <p class="form-label mt-2">Relationship:<span class="redtext">*</span></p>
                                        <div class="flex flex-col">
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Son</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Daughter</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Gender Neutral Child</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Stepson</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Stepdaughter</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Gender neutral stepchild</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p>Date of Birth</p>
                                        <select name="" id="">
                                            <option value="">Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>

                                        </select>

                                        <select name="" id="">
                                            <option value="">Day</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>

                                        <select name="" id="">
                                            <option value="">Year</option>
                                            <option value="1900">1900</option>
                                            <option value="1901">1901</option>
                                            <option value="1902">1902</option>
                                            <option value="1903">1903</option>
                                            <option value="1904">1904</option>
                                            <option value="1905">1905</option>
                                            <option value="1906">1906</option>
                                            <option value="1907">1907</option>
                                            <option value="1908">1908</option>
                                            <option value="1909">1909</option>
                                            <option value="1910">1910</option>
                                            <option value="1911">1911</option>
                                            <option value="1912">1912</option>
                                            <option value="1913">1913</option>
                                            <option value="1914">1914</option>
                                            <option value="1915">1915</option>
                                            <option value="1916">1916</option>
                                            <option value="1917">1917</option>
                                            <option value="1918">1918</option>
                                            <option value="1919">1919</option>
                                            <option value="1920">1920</option>
                                            <option value="1921">1921</option>
                                            <option value="1922">1922</option>
                                            <option value="1923">1923</option>
                                            <option value="1924">1924</option>
                                            <option value="1925">1925</option>
                                            <option value="1926">1926</option>
                                            <option value="1927">1927</option>
                                            <option value="1928">1928</option>
                                            <option value="1929">1929</option>
                                            <option value="1930">1930</option>
                                            <option value="1931">1931</option>
                                            <option value="1932">1932</option>
                                            <option value="1933">1933</option>
                                            <option value="1934">1934</option>
                                            <option value="1935">1935</option>
                                            <option value="1936">1936</option>
                                            <option value="1937">1937</option>
                                            <option value="1938">1938</option>
                                            <option value="1939">1939</option>
                                            <option value="1940">1940</option>
                                            <option value="1941">1941</option>
                                            <option value="1942">1942</option>
                                            <option value="1943">1943</option>
                                            <option value="1944">1944</option>
                                            <option value="1945">1945</option>
                                            <option value="1946">1946</option>
                                            <option value="1947">1947</option>
                                            <option value="1948">1948</option>
                                            <option value="1949">1949</option>
                                            <option value="1950">1950</option>
                                            <option value="1951">1951</option>
                                            <option value="1952">1952</option>
                                            <option value="1953">1953</option>
                                            <option value="1954">1954</option>
                                            <option value="1955">1955</option>
                                            <option value="1956">1956</option>
                                            <option value="1957">1957</option>
                                            <option value="1958">1958</option>
                                            <option value="1959">1959</option>
                                            <option value="1960">1960</option>
                                            <option value="1961">1961</option>
                                            <option value="1962">1962</option>
                                            <option value="1963">1963</option>
                                            <option value="1964">1964</option>
                                            <option value="1965">1965</option>
                                            <option value="1966">1966</option>
                                            <option value="1967">1967</option>
                                            <option value="1968">1968</option>
                                            <option value="1969">1969</option>
                                            <option value="1970">1970</option>
                                            <option value="1971">1971</option>
                                            <option value="1972">1972</option>
                                            <option value="1973">1973</option>
                                            <option value="1974">1974</option>
                                            <option value="1975">1975</option>
                                            <option value="1976">1976</option>
                                            <option value="1977">1977</option>
                                            <option value="1978">1978</option>
                                            <option value="1979">1979</option>
                                            <option value="1980">1980</option>
                                            <option value="1981">1981</option>
                                            <option value="1982">1982</option>
                                            <option value="1983">1983</option>
                                            <option value="1984">1984</option>
                                            <option value="1985">1985</option>
                                            <option value="1986">1986</option>
                                            <option value="1987">1987</option>
                                            <option value="1988">1988</option>
                                            <option value="1989">1989</option>
                                            <option value="1990">1990</option>
                                            <option value="1991">1991</option>
                                            <option value="1992">1992</option>
                                            <option value="1993">1993</option>
                                            <option value="1994">1994</option>
                                            <option value="1995">1995</option>
                                            <option value="1996">1996</option>
                                            <option value="1997">1997</option>
                                            <option value="1998">1998</option>
                                            <option value="1999">1999</option>
                                            <option value="2000">2000</option>
                                            <option value="2001">2001</option>
                                            <option value="2002">2002</option>
                                            <option value="2003">2003</option>
                                            <option value="2004">2004</option>
                                            <option value="2005">2005</option>
                                            <option value="2006">2006</option>
                                            <option value="2007">2007</option>
                                            <option value="2008">2008</option>
                                            <option value="2009">2009</option>
                                            <option value="2010">2010</option>
                                            <option value="2011">2011</option>
                                            <option value="2012">2012</option>
                                            <option value="2013">2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                        </select>
                                    </div>

                                    <div>
                                        <button>Add Child</button>
                                    </div>
                                </div>

                                <!-- Grand Child -->

                                <div>
                                    <p>Identify Grandchildren</p>

                                    <p>Identifying all of your grandchildren is optional. It allows you to choose them later in this wizard if you decide to leave them some of your assets. If you do not plan on leaving anything specific to your grandchildren, you can simply skip this page.</p>

                                    <p>* = required information</p>

                                    <div>
                                        <div>
                                            <input type="radio" name="grand" id="">
                                            <label for="">I have grandchildren, but I am NOT leaving them something directly in my Will</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="grand" id="">
                                            <label for="">I have grandchildren, and I MIGHT OR MIGHT NOT leave them something in my Will</label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="">Grandchild's Full Name</label>
                                        <input type="text">
                                    </div>

                                    <div>
                                        <p class="form-label mt-2">Relationship:<span class="redtext">*</span></p>
                                        <div class="flex flex-col">
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Son</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Daughter</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Gender Neutral Child</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Stepson</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Stepdaughter</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="relationship" class="mr-4"><label for="">Gender neutral stepchild</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p>Date of Birth</p>
                                        <select name="" id="">
                                            <option value="">Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>

                                        </select>

                                        <select name="" id="">
                                            <option value="">Day</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                        </select>

                                        <select name="" id="">
                                            <option value="">Year</option>
                                            <option value="1900">1900</option>
                                            <option value="1901">1901</option>
                                            <option value="1902">1902</option>
                                            <option value="1903">1903</option>
                                            <option value="1904">1904</option>
                                            <option value="1905">1905</option>
                                            <option value="1906">1906</option>
                                            <option value="1907">1907</option>
                                            <option value="1908">1908</option>
                                            <option value="1909">1909</option>
                                            <option value="1910">1910</option>
                                            <option value="1911">1911</option>
                                            <option value="1912">1912</option>
                                            <option value="1913">1913</option>
                                            <option value="1914">1914</option>
                                            <option value="1915">1915</option>
                                            <option value="1916">1916</option>
                                            <option value="1917">1917</option>
                                            <option value="1918">1918</option>
                                            <option value="1919">1919</option>
                                            <option value="1920">1920</option>
                                            <option value="1921">1921</option>
                                            <option value="1922">1922</option>
                                            <option value="1923">1923</option>
                                            <option value="1924">1924</option>
                                            <option value="1925">1925</option>
                                            <option value="1926">1926</option>
                                            <option value="1927">1927</option>
                                            <option value="1928">1928</option>
                                            <option value="1929">1929</option>
                                            <option value="1930">1930</option>
                                            <option value="1931">1931</option>
                                            <option value="1932">1932</option>
                                            <option value="1933">1933</option>
                                            <option value="1934">1934</option>
                                            <option value="1935">1935</option>
                                            <option value="1936">1936</option>
                                            <option value="1937">1937</option>
                                            <option value="1938">1938</option>
                                            <option value="1939">1939</option>
                                            <option value="1940">1940</option>
                                            <option value="1941">1941</option>
                                            <option value="1942">1942</option>
                                            <option value="1943">1943</option>
                                            <option value="1944">1944</option>
                                            <option value="1945">1945</option>
                                            <option value="1946">1946</option>
                                            <option value="1947">1947</option>
                                            <option value="1948">1948</option>
                                            <option value="1949">1949</option>
                                            <option value="1950">1950</option>
                                            <option value="1951">1951</option>
                                            <option value="1952">1952</option>
                                            <option value="1953">1953</option>
                                            <option value="1954">1954</option>
                                            <option value="1955">1955</option>
                                            <option value="1956">1956</option>
                                            <option value="1957">1957</option>
                                            <option value="1958">1958</option>
                                            <option value="1959">1959</option>
                                            <option value="1960">1960</option>
                                            <option value="1961">1961</option>
                                            <option value="1962">1962</option>
                                            <option value="1963">1963</option>
                                            <option value="1964">1964</option>
                                            <option value="1965">1965</option>
                                            <option value="1966">1966</option>
                                            <option value="1967">1967</option>
                                            <option value="1968">1968</option>
                                            <option value="1969">1969</option>
                                            <option value="1970">1970</option>
                                            <option value="1971">1971</option>
                                            <option value="1972">1972</option>
                                            <option value="1973">1973</option>
                                            <option value="1974">1974</option>
                                            <option value="1975">1975</option>
                                            <option value="1976">1976</option>
                                            <option value="1977">1977</option>
                                            <option value="1978">1978</option>
                                            <option value="1979">1979</option>
                                            <option value="1980">1980</option>
                                            <option value="1981">1981</option>
                                            <option value="1982">1982</option>
                                            <option value="1983">1983</option>
                                            <option value="1984">1984</option>
                                            <option value="1985">1985</option>
                                            <option value="1986">1986</option>
                                            <option value="1987">1987</option>
                                            <option value="1988">1988</option>
                                            <option value="1989">1989</option>
                                            <option value="1990">1990</option>
                                            <option value="1991">1991</option>
                                            <option value="1992">1992</option>
                                            <option value="1993">1993</option>
                                            <option value="1994">1994</option>
                                            <option value="1995">1995</option>
                                            <option value="1996">1996</option>
                                            <option value="1997">1997</option>
                                            <option value="1998">1998</option>
                                            <option value="1999">1999</option>
                                            <option value="2000">2000</option>
                                            <option value="2001">2001</option>
                                            <option value="2002">2002</option>
                                            <option value="2003">2003</option>
                                            <option value="2004">2004</option>
                                            <option value="2005">2005</option>
                                            <option value="2006">2006</option>
                                            <option value="2007">2007</option>
                                            <option value="2008">2008</option>
                                            <option value="2009">2009</option>
                                            <option value="2010">2010</option>
                                            <option value="2011">2011</option>
                                            <option value="2012">2012</option>
                                            <option value="2013">2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                        </select>
                                    </div>

                                    <div>
                                        <button>Add Grand Child</button>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                child: 0,
                activeForm: 'sec-2',
                allPages: [
                    'sec-1',
                    'sec-2',
                    'sec-3',
                ],
                mainForm: true,
                selectedOpt: 6,

                qna: {
                    'sec-1': {
                        'Question 1': 'Answer 1',
                        'Question 2': 'Answer 2',
                        'Question 3': 'Answer 3',
                        'Question 4': 'Answer 4',
                        'Question 5': 'Answer 5',
                    },
                    'sec-2': {
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

                openPage(pageName) {
                    switch (pageName) {
                        case 'createMod':
                            this.activeForm = 'createMod';
                            break;
                        case 'sec-1':
                            this.activeForm = 'sec-1';
                            this.mainForm = true;
                            break;
                        default:
                            this.activeForm = 'home';
                            break;
                    }
                },

                selectChanged(e) {
                    let page = e.target.value;

                    switch (page) {
                        case page:

                            break;
                    }
                },

                backPage() {
                    let page = this.allPages.indexOf(this.activeForm);

                    if (page > 0) {
                        let nextPage = page - 1;
                        this.activeForm = this.allPages[nextPage];
                    }
                },

                nextPage() {

                    let page = this.allPages.indexOf(this.activeForm);

                    if (page >= 0 && page < this.allPages.length - 1) {
                        let nextPage = page + 1;
                        this.activeForm = this.allPages[nextPage];
                    }
                },

                save() {

                }
            }))
        })
    </script>
<?php
} else {
    echo '<h2>This content is restricted</h2>';
}
get_footer();
?>