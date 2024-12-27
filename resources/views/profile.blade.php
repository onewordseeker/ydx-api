<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>

<body>
    <nav class="navbar">
        <span class="open-slide">
            <a href="#">
                <span>
                    <a href="#" onclick="openSlideMenu();">
                        <svg width="30" height="30">
                            <path d="M0,5 30,5" stroke="#fff" stroke-width="5" />
                            <path d="M0,14 30,14" stroke="#fff" stroke-width="5" />
                            <path d="M0,23 30,23" stroke="#fff" stroke-width="5" />
                        </svg>
                    </a>
                </span>
    </nav>
    <div id="side-menu" class="side-nav">
        <a href="#" class="btn-close" onclick="closeSlideMenu();">&times;</a>
        <a href="dashboard">Home</a>
        <a href="#">Profile</a>
        <a href="#">Privacy</a>
        <a href="#">Account</a>
        <a href="#">Transactions</a>
        <a href="#">Contact</a>
        <a href="#">Logout</a>
    </div>
    <div class="row">
        <div class="sidebar">
        <div style="height: 100% !important">
            <div class="sidebar-heading">
                Swap Account
            </div>
            <div class="menu">
                <ul>
                    <li><a href="dashboard">Home</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Transactions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="logout" style="margin-top: 100px">
                <span style="color: white; font-size: 20px; margin-right: 10px">&#x21bb;</span><a href="#">Logout</a>
            </div>
        </div>
        </div>
        
        <div class="wrapper">
            <!-- <div class="icons">
                <i class="fa-regular fa-message" style="color: #f4f4f4;"></i>
                <i class="fa fa-gear" style="color: #f4f4f4;"></i>
                <i class="fa-regular fa-user" style="color: #f4f4f4;"></i>
            </div> -->
            <div class="separator">
                <div class="content">
                    <!-- Content of your div goes here -->
                </div>
            </div>
            <div class="main-body">
                <div class="form">
                    <form action="update-profile" enctype="multipart/form-data" method="post" style="display: flex; justify-content: space-between;">
                        <div class="left-form">
                            <div class="form-body">
                                <div class="form-heading">
                                    Profile Details
                                </div>

                                @csrf
                                <div class="form-flex">
                                    <label for="userId">Max User ID</label>
                                    <input type="text" readonly value="{{ $user->id }}" id="userId">
                                    <label for="fname">First Name</label>
                                    <input type="text" id="fname" name="first_name" value="{{ $user->first_name }}" required autofocus autocomplete="first_name">
                                    <label for="lname">Last Name</label>
                                    <input type="text" id="lname" name="last_name" value="{{ $user->last_name }}" required autofocus autocomplete="last_name">
                                    <label for="email">Email</label>
                                    <input type="email" id="emial" name="email" value="{{ $user->email }}" required autofocus autocomplete="email">
                                </div>
                                <div class="form-grid">
                                    <div class="form-grid-left">
                                        <div class="form-flex">
                                            <label for="address">Address</label>
                                            <input type="text" id="address" name="address" value="{{ $user->address }}" required autofocus autocomplete="address">
                                            <label for="zipcode">Zip Code</label>
                                            <input type="number" id="zipcode" name="zipcode" value="{{ $user->zipcode }}" required autofocus autocomplete="zipcode">
                                        </div>
                                    </div>
                                    <div class="form-grid-right">
                                        <div class="form-flex">
                                            <label for="country">Country</label>
                                            <select name="country" id="country">
                                                <option value=""></option>
                                                <option value="Albania">Albania</option>
                                                <option value="Algeria">Algeria</option>
                                                <option value="American Samoa">American Samoa</option>
                                                <option value="Andorra">Andorra</option>
                                                <option value="Angola">Angola</option>
                                                <option value="Anguilla">Anguilla</option>
                                                <option value="Antartica">Antarctica</option>
                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                <option value="Argentina">Argentina</option>
                                                <option value="Armenia">Armenia</option>
                                                <option value="Aruba">Aruba</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Austria">Austria</option>
                                                <option value="Azerbaijan">Azerbaijan</option>
                                                <option value="Bahamas">Bahamas</option>
                                                <option value="Bahrain">Bahrain</option>
                                                <option value="Bangladesh">Bangladesh</option>
                                                <option value="Barbados">Barbados</option>
                                                <option value="Belarus">Belarus</option>
                                                <option value="Belgium">Belgium</option>
                                                <option value="Belize">Belize</option>
                                                <option value="Benin">Benin</option>
                                                <option value="Bermuda">Bermuda</option>
                                                <option value="Bhutan">Bhutan</option>
                                                <option value="Bolivia">Bolivia</option>
                                                <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                                                <option value="Botswana">Botswana</option>
                                                <option value="Bouvet Island">Bouvet Island</option>
                                                <option value="Brazil">Brazil</option>
                                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                <option value="Bulgaria">Bulgaria</option>
                                                <option value="Burkina Faso">Burkina Faso</option>
                                                <option value="Burundi">Burundi</option>
                                                <option value="Cambodia">Cambodia</option>
                                                <option value="Cameroon">Cameroon</option>
                                                <option value="Canada">Canada</option>
                                                <option value="Cape Verde">Cape Verde</option>
                                                <option value="Cayman Islands">Cayman Islands</option>
                                                <option value="Central African Republic">Central African Republic</option>
                                                <option value="Chad">Chad</option>
                                                <option value="Chile">Chile</option>
                                                <option value="China">China</option>
                                                <option value="Christmas Island">Christmas Island</option>
                                                <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                                                <option value="Colombia">Colombia</option>
                                                <option value="Comoros">Comoros</option>
                                                <option value="Congo">Congo</option>
                                                <option value="Congo">Congo, the Democratic Republic of the</option>
                                                <option value="Cook Islands">Cook Islands</option>
                                                <option value="Costa Rica">Costa Rica</option>
                                                <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                                                <option value="Croatia">Croatia (Hrvatska)</option>
                                                <option value="Cuba">Cuba</option>
                                                <option value="Cyprus">Cyprus</option>
                                                <option value="Czech Republic">Czech Republic</option>
                                                <option value="Denmark">Denmark</option>
                                                <option value="Djibouti">Djibouti</option>
                                                <option value="Dominica">Dominica</option>
                                                <option value="Dominican Republic">Dominican Republic</option>
                                                <option value="East Timor">East Timor</option>
                                                <option value="Ecuador">Ecuador</option>
                                                <option value="Egypt">Egypt</option>
                                                <option value="El Salvador">El Salvador</option>
                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                <option value="Eritrea">Eritrea</option>
                                                <option value="Estonia">Estonia</option>
                                                <option value="Ethiopia">Ethiopia</option>
                                                <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                                                <option value="Faroe Islands">Faroe Islands</option>
                                                <option value="Fiji">Fiji</option>
                                                <option value="Finland">Finland</option>
                                                <option value="France">France</option>
                                                <option value="France Metropolitan">France, Metropolitan</option>
                                                <option value="French Guiana">French Guiana</option>
                                                <option value="French Polynesia">French Polynesia</option>
                                                <option value="French Southern Territories">French Southern Territories</option>
                                                <option value="Gabon">Gabon</option>
                                                <option value="Gambia">Gambia</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Germany">Germany</option>
                                                <option value="Ghana">Ghana</option>
                                                <option value="Gibraltar">Gibraltar</option>
                                                <option value="Greece">Greece</option>
                                                <option value="Greenland">Greenland</option>
                                                <option value="Grenada">Grenada</option>
                                                <option value="Guadeloupe">Guadeloupe</option>
                                                <option value="Guam">Guam</option>
                                                <option value="Guatemala">Guatemala</option>
                                                <option value="Guinea">Guinea</option>
                                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                <option value="Guyana">Guyana</option>
                                                <option value="Haiti">Haiti</option>
                                                <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                                                <option value="Holy See">Holy See (Vatican City State)</option>
                                                <option value="Honduras">Honduras</option>
                                                <option value="Hong Kong">Hong Kong</option>
                                                <option value="Hungary">Hungary</option>
                                                <option value="Iceland">Iceland</option>
                                                <option value="India">India</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Iran">Iran (Islamic Republic of)</option>
                                                <option value="Iraq">Iraq</option>
                                                <option value="Ireland">Ireland</option>
                                                <option value="Israel">Israel</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Jamaica">Jamaica</option>
                                                <option value="Japan">Japan</option>
                                                <option value="Jordan">Jordan</option>
                                                <option value="Kazakhstan">Kazakhstan</option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Kiribati">Kiribati</option>
                                                <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
                                                <option value="Korea">Korea, Republic of</option>
                                                <option value="Kuwait">Kuwait</option>
                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                <option value="Lao">Lao People's Democratic Republic</option>
                                                <option value="Latvia">Latvia</option>
                                                <option value="Lebanon" selected>Lebanon</option>
                                                <option value="Lesotho">Lesotho</option>
                                                <option value="Liberia">Liberia</option>
                                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                <option value="Liechtenstein">Liechtenstein</option>
                                                <option value="Lithuania">Lithuania</option>
                                                <option value="Luxembourg">Luxembourg</option>
                                                <option value="Macau">Macau</option>
                                                <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                                                <option value="Madagascar">Madagascar</option>
                                                <option value="Malawi">Malawi</option>
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Maldives">Maldives</option>
                                                <option value="Mali">Mali</option>
                                                <option value="Malta">Malta</option>
                                                <option value="Marshall Islands">Marshall Islands</option>
                                                <option value="Martinique">Martinique</option>
                                                <option value="Mauritania">Mauritania</option>
                                                <option value="Mauritius">Mauritius</option>
                                                <option value="Mayotte">Mayotte</option>
                                                <option value="Mexico">Mexico</option>
                                                <option value="Micronesia">Micronesia, Federated States of</option>
                                                <option value="Moldova">Moldova, Republic of</option>
                                                <option value="Monaco">Monaco</option>
                                                <option value="Mongolia">Mongolia</option>
                                                <option value="Montserrat">Montserrat</option>
                                                <option value="Morocco">Morocco</option>
                                                <option value="Mozambique">Mozambique</option>
                                                <option value="Myanmar">Myanmar</option>
                                                <option value="Namibia">Namibia</option>
                                                <option value="Nauru">Nauru</option>
                                                <option value="Nepal">Nepal</option>
                                                <option value="Netherlands">Netherlands</option>
                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                <option value="New Caledonia">New Caledonia</option>
                                                <option value="New Zealand">New Zealand</option>
                                                <option value="Nicaragua">Nicaragua</option>
                                                <option value="Niger">Niger</option>
                                                <option value="Nigeria">Nigeria</option>
                                                <option value="Niue">Niue</option>
                                                <option value="Norfolk Island">Norfolk Island</option>
                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                <option value="Norway">Norway</option>
                                                <option value="Oman">Oman</option>
                                                <option value="Pakistan">Pakistan</option>
                                                <option value="Palau">Palau</option>
                                                <option value="Panama">Panama</option>
                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                <option value="Paraguay">Paraguay</option>
                                                <option value="Peru">Peru</option>
                                                <option value="Philippines">Philippines</option>
                                                <option value="Pitcairn">Pitcairn</option>
                                                <option value="Poland">Poland</option>
                                                <option value="Portugal">Portugal</option>
                                                <option value="Puerto Rico">Puerto Rico</option>
                                                <option value="Qatar">Qatar</option>
                                                <option value="Reunion">Reunion</option>
                                                <option value="Romania">Romania</option>
                                                <option value="Russia">Russian Federation</option>
                                                <option value="Rwanda">Rwanda</option>
                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                <option value="Saint LUCIA">Saint LUCIA</option>
                                                <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                                                <option value="Samoa">Samoa</option>
                                                <option value="San Marino">San Marino</option>
                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                <option value="Senegal">Senegal</option>
                                                <option value="Seychelles">Seychelles</option>
                                                <option value="Sierra">Sierra Leone</option>
                                                <option value="Singapore">Singapore</option>
                                                <option value="Slovakia">Slovakia (Slovak Republic)</option>
                                                <option value="Slovenia">Slovenia</option>
                                                <option value="Solomon Islands">Solomon Islands</option>
                                                <option value="Somalia">Somalia</option>
                                                <option value="South Africa">South Africa</option>
                                                <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                                                <option value="Span">Spain</option>
                                                <option value="SriLanka">Sri Lanka</option>
                                                <option value="St. Helena">St. Helena</option>
                                                <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                                                <option value="Sudan">Sudan</option>
                                                <option value="Suriname">Suriname</option>
                                                <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                                                <option value="Swaziland">Swaziland</option>
                                                <option value="Sweden">Sweden</option>
                                                <option value="Switzerland">Switzerland</option>
                                                <option value="Syria">Syrian Arab Republic</option>
                                                <option value="Taiwan">Taiwan, Province of China</option>
                                                <option value="Tajikistan">Tajikistan</option>
                                                <option value="Tanzania">Tanzania, United Republic of</option>
                                                <option value="Thailand">Thailand</option>
                                                <option value="Togo">Togo</option>
                                                <option value="Tokelau">Tokelau</option>
                                                <option value="Tonga">Tonga</option>
                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                <option value="Tunisia">Tunisia</option>
                                                <option value="Turkey">Turkey</option>
                                                <option value="Turkmenistan">Turkmenistan</option>
                                                <option value="Turks and Caicos">Turks and Caicos Islands</option>
                                                <option value="Tuvalu">Tuvalu</option>
                                                <option value="Uganda">Uganda</option>
                                                <option value="Ukraine">Ukraine</option>
                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="United States">United States</option>
                                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                <option value="Uruguay">Uruguay</option>
                                                <option value="Uzbekistan">Uzbekistan</option>
                                                <option value="Vanuatu">Vanuatu</option>
                                                <option value="Venezuela">Venezuela</option>
                                                <option value="Vietnam">Viet Nam</option>
                                                <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                                                <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                                                <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                                                <option value="Western Sahara">Western Sahara</option>
                                                <option value="Yemen">Yemen</option>
                                                <option value="Serbia">Serbia</option>
                                                <option value="Zambia">Zambia</option>
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                            <label for="contact">Contact Number</label>
                                            <input type="number" id="contact" name="contact" value="{{ $user->contact }}" required autofocus autocomplete="contact">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-flex">
                                    <label for="dob">Date of Birth (YYYY-MM-DD)</label>
                                    <input type="date" name="dob" value="{{ $user->dob }}" required autofocus autocomplete="dob">
                                </div>
                                <div class="form-grid">
                                    <div class="form-grid-left">
                                        <div class="form-flex">
                                            <label for="gender">Gender</label>
                                            <select id="gender" name="gender" value="{{ $user->gender }}" required>
                                                <option value=""></option>
                                                <option>Male</option>
                                                <option>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-grid-right">
                                        <div class="form-flex">
                                            <label for="id">ID Number</label>
                                            <input type="number" id="id" name="idnumber" value="{{ $user->idnumber }}" required autofocus autocomplete="idnumber">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-button">
                                    <input type="submit" value="Update">
                                </div>
                            </div>
                        </div>

                        <div class="right-form">
                            <div class="right-form-body">
                                <div class="form-heading">
                                    Profile Picture
                                </div>
                                <div class="right-form-flex">
                                    <div class="upload">
                                        <label for="passport">Picture</label>
                                        <img src="{{ asset('uploads/'.$user->passport_picture) }}" style=" width: 250px" />
                                        <p>Upload Your Passport</p>
                                    </div>
                                    <hr>
                                    <div class="upload">
                                        <label for="dashboard">Picture</label>
                                        <img src="{{ asset('uploads/'.$user->max_screenshot) }}" style=" width: 250px" />
                                        <p>Upload A screenshot of Max Dashboard</p>
                                    </div>
                                </div>
                            </div>
                            <div class="right-bottom">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium iure earum at temporibus aliquam dolorum labore aut eveniet doloremque, eligendi provident tempora consectetur. Iure mollitia nihil numquam praesentium accusantium assumenda.
                                    Iste cumque aspernatur cum deserunt quasi. Vero architecto provident corporis delectus maiores et deleniti dicta nam molestiae, harum quod. Dignissimos, possimus repellendus. Voluptas officia beatae repudiandae doloremque laudantium, quia aspernatur!
                                    Ad obcaecati commodi dolorem natus sunt ut, itaque sequi autem officiis qui quidem magnam ex consequatur a fugit reprehenderit laboriosam doloremque minus illo nisi architecto. Iste quaerat non ducimus? Provident.
                                    Facere, ab enim. A quasi veritatis reiciendis odio eveniet quas vel totam velit corporis nisi, officia, excepturi amet officiis, delectus repellendus ipsam labore. Magnam laboriosam ex saepe, corporis doloremque facere.
                                    Quo temporibus ex inventore, repudiandae maxime quae quibusdam at a soluta, recusandae eos in nihil et tenetur labore commodi. Quod ratione nesciunt, magni dignissimos ad veniam dicta maiores eos id.</p>
                                <br>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt impedit quos, modi eum voluptates ipsum rerum quisquam. Aliquid similique neque provident, totam minima iure autem unde, impedit, expedita temporibus pariatur!
                                    In ab, eveniet quod quas exercitationem ut! Minima debitis beatae fugit consequuntur, obcaecati quasi magni repudiandae, libero necessitatibus nisi similique veritatis aut sed repellendus molestias? Ratione modi aspernatur eaque nemo!</p>
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <script src="https://kit.fontawesome.com/8f90b118b8.js" crossorigin="anonymous"></script>
        <script>
            function openSlideMenu() {
                document.getElementById('side-menu').style.width = '250px';
                document.getElementById('main').style.marginLeft = '250px';
            }

            function closeSlideMenu() {
                document.getElementById('side-menu').style.width = '0';
                document.getElementById('main').style.marginLeft = '0';
            }
        </script>
    </footer>
</body>

</html>