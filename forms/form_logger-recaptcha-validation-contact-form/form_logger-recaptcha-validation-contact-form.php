<?php
        if (!empty($_POST)) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $privatekey = "SECRET KEY";
            $response = file_get_contents($url."?secret=".$privatekey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
            $data = json_decode($response);

            if (isset($data->success) AND $data->success==true) {

                global $siteData, $siteDefineData;
                require_once ENV_ROOT.'/portal/libraries/formLogger.api.php';
                $logger = new formLoggerApi();
                $logger->setSiteId($siteData['site.id']);
                $logger->setSessionId($siteDefineData['cms_tracking_sessions']['session.id']);
                $logger->setFormId('form_logger_');
                $logger->setFormName('SET FORM NAME');
                $logger->setcustomEmailSubject('SET EMAIL SUBJECT LINE');
                $logger->setNotificationEmailAddresses('SET NOTIFICATION EMAIL ADDRESS'); 
   
                if ($logger->saveData($_POST)) {
                    echo '<style>.form-wrapper{display:none}.row.submit{text-align: center;}</style>
                    <div class="row submit">
                    <div class="columns">
                    <h3>Thank you for your interest in [company]</h3><p>Our team will reach out to you shortly regarding your request.</p>
                    </div>
                    </div>';
                } else {
                    echo '<style>.form-wrapper{display:none}.row.submit{text-align: center;}</style>
                    <div class="row submit">
                    <div class="columns">
                    <h3>Oops, it looks like something went wrong.</h3>
                    <p>Please try submitting the form again.</p>
                    </div>
                    </div>';
                }
            }
        }
        ?>
<style>
#content-wrap {
    position: relative;
    overflow: hidden;
}
#content-wrap .columns.center {
    margin-top: 0;
    margin-bottom: 0;
}
/* General Form Styles */
#contact-form {
    max-width: 900px;
    margin: auto;
    background: rgba(255,255,255,.75);
    padding: 20px;
}
#contact-form .input-wrap:not(.submit){
    background:#fff;
    height:65px;
    border:1px solid #E2E2E2;
    border-radius:3px;
    box-shadow:0 3px 10px rgba(0,
    0,
    0,
    .1);
}
#contact-form label{
    font-size:11px;
    line-height:100%;
    font-weight:600;
    color:#1A428A;
    padding:12px 0 0 12px;
    position:relative;
    z-index:1;
}
#contact-form input:not(.submit),
#contact-form textarea{
    border:0;
    box-shadow:none;
    margin:0 !important;
    background:transparent;
    height:65px;
    font-size:18px;
    padding:25px 12px 0;
    top:-25px;
    position:relative;
}
#contact-form .comments .input-wrap {
    overflow: auto;
    height: 100% !important;
}
#contact-form textarea {
    height: auto;
    padding-top: 10px;
    padding-bottom: 10px;
    top: 0;
}
#contact-form input[type=submit]{
    width: 200px;
    height: 65px;
    border-radius: 3px !important;
    margin: 20px auto 0 !important;
    padding: 0 !important;
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 600;
    background-color: #1A428A;
    cursor: pointer;
    display: block;
}
#contact-form input[type=submit]:hover{
    background-color:#0748ba;
}
#contact-form .input-wrap {
    margin-bottom: 50px;
}
#contact-form select {
    background: none;
    border: 0;
    padding-left: 11px;
    font-family: benton-sans, sans-serif;
    font-size: 18px;
    position: relative;
    height: 65px;
    top: -24px;
    padding-top: 30px;
    margin: 0;
}
#contact-form .input-wrap.dropdown::after {
    content: '';
    display: block;
    border: 5px solid transparent;
    border-top: 5px solid #1a4289;
    position: absolute;
    top: 30px;
    right: 40px;
    z-index: 100;
    pointer-events: none;
}
#contact-form .contact_form_outro {
    font-size: 12px;
    color: #979797;
    text-align: center;
    line-height: 1.5em;
    margin-top: 20px;
}
/** Validation styling **/
input.parsley-error,
input.mce_inline_error,
#contact-form input.error,
#contact-form select.error{
    background:#fffed9 !important;
    border:1px solid tomato !important;
}
ul.parsley-errors-list li,
div.mce_inline_error,
#contact-form label.error {
    font-size:11px;
    color:tomato;
    font-weight:600;
}
input.mce_inline_error {
    top:-24px !important;
    position:relative;
    left:-1px;
    width:calc(100% + 2px);
}
ul.parsley-errors-list {
    margin:-20px 0 0;
    list-style:none;
}
#contact-form label.error {
    top:-33px;
    left:-10px
}
#mc_embed_signup div.mce_inline_error {
    background:none !important;
    color:tomato !important;
    left:-10px;
    position:relative !important;
    margin:0 !important;
}
@media screen and (max-width: 640px){
    #contact-form .input-wrap,
    #contact-form input,
    #contact-form select{
        height: 55px !important;
    }
    #contact-form input,
    #contact-form input.submit,
    #contact-form select {
        font-size: 15px !important;
    }
    #contact-form select + label.error {
        top: -52px;
    }
    .columns.name:first-child,
    .columns.phone {
        padding-right:  8px;
    }
    .columns.name:nth-child(2),
    .columns.zip{
        padding-left: 8px;
    }
    #contact-form select {
        padding-top: 20px;
        padding-bottom: 0;
        height: 55px;
    }
}
</style>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script type="text/javascript" src="/core/js/jquery.validate.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/additional-methods.min.js"></script>
<script type="text/javascript">
        $(document).ready(function () {
            $('#form_logger_Contact_Form_Request').validate({
                rules: {
                    form_logger_First_Name: {
                        required: true,
                        lettersonly: true
                    },
                    form_logger_Last_Name: {
                        required: true,
                        lettersonly: true
                    },
                    form_logger_Company: {
                        required: true
                    },
                    interested_in: {
                        required: true
                    },
                    form_logger_Email: {
                        required: true,
                        email: true,
                    },
                    form_logger_Phone: {
                        required: true,
                        phoneUS: true
                    },
                    form_logger_City: {
                        required: true,
                        lettersonly: true
                    },
                    form_logger_State: {
                        required: true
                    },
                    form_logger_Zip_Code: {
                        required: true,
                        zipcodeUS: true
                    }
                },
                messages: {
                    form_logger_First_Name: {
                        required: 'First name is required' 
                    },
                    form_logger_Last_Name: {
                        required: 'Last name is required'
                    },
                    form_logger_Company: {
                        required: 'Company name is required'
                    },
                    interested_in: {
                        required: 'This field is required'
                    },
                    form_logger_Email: {
                        required: 'Email address is required'
                    },
                    form_logger_Phone: {
                        required: 'Phone number is required'
                    },
                    form_logger_City: {
                        required: 'City is required'
                    },
                    form_logger_State: {
                        required: 'State is required'
                    },
                    form_logger_Zip_Code: {
                        required: 'Zip code is required'
                    }
                }
            });
        });
        function onSubmit(token) {
        document.querySelector('input[name="g-recaptcha-response"]').value = token;
        if($('#form_logger_Contact_Form_Request').valid() == true) {
            document.getElementById("form_logger_Contact_Form_Request").submit();
        }
    }
    </script>
    <div class="form-wrapper">
    <div class="row">
            <div class="columns medium-6 medium-centered center">
                <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                <p>Beatae omnis tempore ut enim eligendi at dolorem nobis maxime aliquam suscipit quidem doloremque expedita minus, minima, reiciendis corrupti eos neque iusto.</p>
            </div>
        </div>
    <div id="contact-form">
        <form action=""
            method="post" id="form_logger_Contact_Form_Request">
            <input type="hidden" name="save" value="save">
            <input type="hidden" name="g-recaptcha-response" value="">
            <div class="row">
                <div class="columns small-6 name">
                        <div class="input-wrap">
                            <label for="Name">First Name <span>*</span></label>
                            <input type="text" name="form_logger_First_Name" maxlength="50" class="required">
                        </div>
                </div>
                <div class="columns small-6 name">
                    <div class="input-wrap">
                        <label for="Name">Last Name <span>*</span></label>
                        <input type="text" name="form_logger_Last_Name" maxlength="50" class="required" id="Last-Name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns medium-6 business-name">
                    <div class="input-wrap">
                        <label for="Business-Name">Company <span>*</span></label>
                        <input type="text" name="form_logger_Company" maxlength="50" class="required" id="Business-Name">
                    </div>
                </div>
                <div class="columns medium-6 interested-in">
                    <div class="input-wrap dropdown">
                        <label for="interested-in">I'm interested in <span>*</span></label>
                        <select id="interest-topics-list" name="form_logger_interested_in" class="required">
                            <option value="">Please select...</option>
                            <option value="Dealership Opportunities">Dealership Opportunities</option>
                            <option value="Business Training">Business Training</option>
                            <option value="Internet Marketing Services">Internet Marketing Services</option>
                            <option value="MoreHouse Financing">MoreHouse Financing</option>
                            <option value="Other">Other Questions</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns medium-6 email">
                    <div class="input-wrap">
                        <label for="Email">Email <span>*</span></label>
                        <input type="text" name="form_logger_Email" maxlength="50" class="required" id="Email">
                    </div>
                </div>
                <div class="columns medium-6 phone">
                    <div class="input-wrap">
                        <label for="Phone">Phone <span>*</span></label>
                        <input type="text" name="form_logger_Phone" maxlength="50" class="required" id="phone">
                    </div>
                </div>
                <div class="columns medium-4 city">
                    <div class="input-wrap">
                        <label for="City">City <span>*</span></label>
                        <input type="text" name="form_logger_City" maxlength="50" class="required" id="City">
                    </div>
                </div>
                <div class="columns small-6 medium-4 state">
                    <div class="input-wrap">
                        <label for="State">State/Province <span>*</span></label>
                        <select name="form_logger_State" class="required" id="State">
                            <option value="">Choose State...</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                            <option value="AB">Alberta</option>
                            <option value="BC">British Columbia</option>
                            <option value="MB">Manitoba</option>
                            <option value="NB">New Brunswick</option>
                            <option value="NL">Newfoundland and Labrador</option>
                            <option value="NS">Nova Scotia</option>
                            <option value="ON">Ontario</option>
                            <option value="PE">Prince Edward Island</option>
                            <option value="QC">Quebec</option>
                            <option value="SK">Saskatchewan</option>
                            <option value="NT">Northwest Territories</option>
                            <option value="NU">Nunavut</option>
                            <option value="YT">Yukon</option>
                        </select>
                    </div>
                </div>
                <div class="columns small-6 medium-4 zip">
                    <div class="input-wrap">
                        <label for="Zip_Code">Zip/Postal Code <span>*</span></label>
                        <input type="text" name="form_logger_Zip_Code" maxlength="50" class="required" id="zip">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns comments">
                    <div class="input-wrap">
                        <label for="Comments">Questions &amp; Comments </label>
                        <textarea name="form_logger_Comments" id="Comments" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    <div class="input-wrap submit">
                    <input class="g-recaptcha formhandler__submit" 
                        data-sitekey="SITE KEY" 
                        data-callback='onSubmit' 
                        data-action='submit' name="save" type="submit" value="Submit" id="save">
                    </div>
                </div>
            </div>
        </form>
        <div class="contact_form_outro"></div>
    </div>
    </div>
