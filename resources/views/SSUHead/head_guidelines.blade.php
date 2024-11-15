<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Guidelines</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    @include('SSUHead.partials.sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="cont-g">
            <div class="box-g">
                <h2 class="guide-title-2">PARKING AND TRAFFIC GUIDELINES</h2>
                <h3 class="guide-title-3">ADMINISTRATIVE ORDER #384 S.2021</h3>
                <!-- <ul>
                    <li class="guide-title-2">PARKING AND TRAFFIC GUIDELINES</li>
                    <li class="guide-title-3">ADMINISTRATIVE ORDER #384 S.2021</li>
                </ul> -->
            </div>
        </div>

        <div class="cont-g-2">
            <div class="dropdown-section">
                <div class="srch-arw">
                    <input type="text" id="sectionSearch" placeholder="Search for guidelines..." onkeyup="searchGuidelines()">
                    <button onclick="prevMatch()" class="srch-btn">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button onclick="nextMatch()" class="srch-btn">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                    <p id="matchInfo"></p>
                </div>
                <select id="sectionDropdown" onchange="location = this.value;">
                    <option value="#section3">Section 3</option>
                    <option value="#section4">Section 4</option>
                    <option value="#section5">Section 5</option>
                    <option value="#section6">Section 6</option>
                    <option value="#section7">Section 7</option>
                    <option value="#section8">Section 8</option>
                    <option value="#section9">Section 9</option>
                    <option value="#section10">Section 10</option>
                    <option value="#section11">Section 11</option>
                    <option value="#section12">Section 12</option>
                    <option value="#section13">Section 13</option>
                    <option value="#section14">Section 14</option>
                    <option value="#section15">Section 15</option>
                    <option value="#section16">Section 16</option>
                    <option value="#section17">Section 17</option>
                    <option value="#section18">Section 18</option>
                    <option value="#section19">Section 19</option>
                    <option value="#section20">Section 20</option>
                    <option value="#section21">Section 21</option>
                    <option value="#section22">Section 22</option>
                    <option value="#section23">Section 23</option>
                    <option value="#section24">Section 24</option>
                    <option value="#section25">Section 25</option>
                </select>
            </div>
            <div class="box-g-2">
                <h3>PARKING GUIDELINES</h3>

                <h4 id="section3">Section 3. Designation of Appropriate Parking Space</h4>
                <p>Parking of motorized and non-motorized vehicle is allowed
                only at designated parking areas for employees, visitors and
                other University stakeholders. The concerned Deans/Directors
                shall designate authorized parking space within their
                premises. For GASS, the Vice President for Administration and
                Finance shall designate authorized parking space and
                for BU Auxiliary, the Vice President for Academic Affairs.
                </p>

                <h4 id="section4">Section 4. BU Motorpool Parking</h4>
                <p>The designated parking for BU Motorpool vehicles shall be used
                exclusively for University vehicles only. Any driver who parks a
                vehicle in the area reserved for BU vehicles shall be penalized
                in accordance with the succeeding sections.
                </p>

                <h4 id="section5">Section 5. Non-assumption of any liability for loss of things 
                or damages in in parking spaces</h4>
                <p>The University assumes no liability for any loss or damage
                to privately owned vehicles parked within the University
                property.</p>

                <h4 id="section6">Section 6. Authority to Restrict Use of Parking Spaces</h4>
                <p>The University may restrict or otherwise control as necessary,
                the use of parking spaces or lots for University needs and
                special events.</p>

                <h4 id="section7">Section 7. Prohibition on Overnight Parking</h4>
                <p>Overnight parking is strictly prohibited.Cars may be allowed to
                park overnight at designated overnight parking areas only in
                cases of emergency and official school functions. The
                University is not responsible for any loss or damage to
                vehicles parked overnight inside its premises in cases of
                authorized overnight parking, the driver applicant shall
                execute a waiver of liability to the University for any loss
                or damage fo vehicle parked overnight inside its
                premises

                <p style="font-style:italic">In cases where overnight parking is allowed, the following
                procedures shall be observed:</p>
                <p class="check">The applicant shall accomplish the Application for Overnight Parking permit (Annex A) and
                submit the same to the concerned office.</p>
                <p  class="check">The Vice-President for Administration and Finance (VPAF) for GASS, the Vice-President for
                Academic Affairs (VPAA) for Auxiliary, the Vice-President for Research Development and
                Extension (VPRDE) for East Campus, and the Deans and Directors for external campuses and
                Campus/Cluster Administrative Officers in the main and Daraga campuses are authorized
                to act on the applications for overnight parking permit.</p>
                <p class="check">Once approved, the applicant for overnight parking shall furnish a copy of the approved
                overnight parking to the Guard on duty.</p>
                <p class="check">Guards on Duty must check the overnight parking permit of vehicles parked overnight</p>
                <p class="check">Vehicles must be parked properly and may not occupy two parking slots. Parking in any
                manner which obstructs the free flow of vehicles is prohibited. Ensure that the vehicle is
                parked within the space allotted for one vehicle only.</p>

                <h4 id="section8">Section 8. Reserved Parking Spaces</h4>
                <p>Parking of motorized and non-motorized vehicles in reserved
                parking areas or spaces is not allowed. For this purpose,
                the Vice-Presidents, Deans and Directors are authorized to
                designate reserved parking areas in their respective jurisdictions.</p>

                <h4 id="section9">Section 9. Prohibited Parking on Sidewalks and Driveways</h4>
                <p>Parking on sidewalks and driveways is strictly prohibited.
                Violation of this sections shall be penalized in accordance
                with the succeeding sections.</p>

                <h4 id="section10">Section 10. Vehicle Sticker</h4>
                <p>The University shall issue a vehicle sticker to vehicles authorized
                to enter the University premises. Car stickers shall be issued for
                free to BU employees for one (1) vehicle only for every employee.
                Employees with more than one vehicle shall be required to pay
                the prescribed amount for the additional vehicle/s. Car stickers
                shall be valid for one (1) year only.</p>

                <p style="font-style: italic";>Below is the prescribed vehicles sticker fee:</p>
                <div style="display: flex; justify-content: center;">
                    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; text-align: center;">
                        <thead>
                            <tr>
                                <th colspan="3" style="background-color: #FFA500; color: white;">VEHICLE STICKER FEES</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th style="color: #09b3e4;">4-wheel vehicles</th>
                                <th style="color: #09b3e4;">Motorcycle / Tricycles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight: bold; color: #09b3e4;">BU Employees</td>
                                <td style="color: #566a7f">P 350.00</td>
                                <td style="color: #566a7f">P 150.00</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; color: #09b3e4;">Non-BU employees</td>
                                <td style="color: #566a7f">P 500.00</td>
                                <td style="color: #566a7f">P 250.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p style="font-style: italic";>Applications for vehicle sticker, either for free or for a fee, shall be made at the
                Motorpool Section following the procedures below:</p>
                <p class="check">The applicant shall submit the accomplished Application for Vehicle Sticker (Annex B) together with the
                photocopy of the motor vehicle's Official Receipt (OR)/Certificate of Registration (CR) and owner's driver's
                license to the BU Motorpool Section.</p>
                <p class="check">The Head of the Motorpool Section shall act on the applications for vehicle sticker.</p>
                <p class="check">Once approved, the applicant shall pay the corresponding amount ( applicable) at the University Cashiers
                Office located at the Ricardo A. Arcilla Building (formerly GASS Building) Copy of official receipt shall be
                provided to the Motarpool Section for processing of vehicle sticker.</p>
                <p class="check">The BU Motorpool Section shall then issue the vehicle sticker to the driver applicant.</p>
                <p style="font-style: italic";>The Motorpool Section, Security Services Unit and the Business Affairs Office (BAO) are hereby directed
                to come up with the design of the vehicle sticker.</p>

                <hr>
                <h3>TRAFFIC RULES AND GUIDELINES</h3>

                <h4 id="section11">Section 11. Implementation of Traffic Rules and Regulations</h4>
                <p>All parking and traffic rules and regulations will be implemented
                anytime of the day and night.</p>

                <h4 id="section12">Section 12. Observance of due diligence in driving</h4>
                <p>All drivers are expected to exercise due caution on all parts of
                the University premises with particular regard for the safety of
                all pedestrians.</p>

                <h4 id="section13">Section 13. University Speed Limit</h4>
                <p>The speed limit in all University roads shall be 15 kilometers per
                hour (kph) Such speed limit shall however be reduced at
                crosswalks, buildings and parking lot entrances and in parking
                lots or other congested areas.</p>

                <h4 id="section14">Section 14. Driver's Identity Verification</h4>
                <p>Security guards on duty have the authority to verify the identity
                of drivers and their respective vehicles entering the University
                premises A valid Identification Card (I.D.) can be requested
                from the person by the guard on duty when entering the
                University premises.</p>

                <h4 id="section15">Section 15. Loading and Unloading Area</h4>
                <p>The Vice-Presidents, Deans and Directors shall designate
                appropriate loading and unloading areas in their respective
                jurisdictions. Violation of this section shall also be penalized in
                accordance with the succeeding sections.</p>

                <h4 id="section16">Section 16. Other Guidelines for Driver's Observance.</h4>
                <p style="font-style: italic";>The drivers shall further observed the following guidelines:</p>
                <p class="not">Use of cellular phones while driving is strictly prohibited.</p>
                <p class="not">Driving under the influence of alcohol or drugs is strictly prohibited.</p>
                <p class="check">Drivers must follow all traffic and street signs inside the campus. Alteration, removal
                and defacing any of the signage are prohibited.
                </p>
                <p class="check">Drivers inside the campus must observe all applicable traffic rules and ordinances
                of the Local Government in addition to the rules and regulations of the University.</p>
                <p class="check">Driving, road and pedestrian courtesy must be observed at all times. Threatening or
                verbal abuse of students, fellow drivers, parents, traffic enforcers/security guards,
                or any member of the university community is considered a violation of
                University traffic rules and regulations</p>

                <hr>
                <h3>ENFORCEMENT MECHANISM</h3>

                <h4 id="section17">Section 17. Office tasked fo monitor the implementation</h4>
                <p>The Security Services Unit (SSU) is authorized to strictly enforce
                and monitor the University traffic and parking regulations.</p>

                <h4 id="section18">Section 18. Towing of illegally parked vehicles</h4>
                <p>Bicol University reserves the right to tow away or impound any
                vehicle parked in violation of University rules and to impose
                such fines as may be specified<br>
                For this purpose and until the University is able to procure its
                own towing equipment, the University shall establish a
                Memorandum of Agreement with the concerned Local
                Government Units which have sufficient towing equipment.<br>
                For colleges/campuses located in LGUs with no towing
                equipment, strict implementation of the penalty provisions
                herein provided shall be observed</p>

                <h4 id="section19">Section 19. Punishable Acts and Offenses</h4>
                <p style="font-style: italic";>The following are the punishable acts and offenses under
                these guidelines:</p>

                <h5 style="color:#566a7f">A. Parking Violations</h5>
                <p>i. Unauthorized parking/overnight parking on campus.<br>
                ii. Parking at a NO parking zone.<br>
                iii, Parking on sidewalk and driveway.<br>
                iv. Parking in reserved areas.<br>
                v. Improperly parked vehicle (ex. double parking, parking occupying two
                lanes, etc.)</p>

                <h5 style="color:#566a7f">B. Traffic Violations</h5>
                <p>i. Reckless driving.<br>
                ii. Violation of speed limit.<br>
                iii. Failure to give way to pedestrian on pedestrian lanes.<br>
                iv. Disregarding traffic sign or traffic enforcers/guard on duty.<br>
                v. Obstructing traffic: driving against the traffic or blocking traffic.<br>
                vi. Driving vehicle on sidewalk & pedestrian walkway.<br>
                vii. Loading or unloading at NO loading/unloading zones.<br>
                viii. Driving under the influence of prohibited substances.<br>
                ix. Discourtesy/verbal abuse towards other drivers, students, pedestrians,
                or traffic enforcers/guard on duty.<br>
                x. Vandalism, removal or defacement of street signs.<br>
                xi. Counter flowing.<br>
                xii. Blowing of horns.</p>

                <h5 style="color:#566a7f">C. Other Violations</h5>
                <p>i. Fake/altered vehicle sticker.<br>
                ii. Vehicle sticker was used for not for the vehicle it was issued.</p>

                <h4 id="section20">Section 20. Apprehension of Violators</h4>
                <p>The guard on duty/personnel designated to enforce these parking
                rules and regulation shall hand a violation ticket to the
                driver/person or place a violation ticket (Annex C) on the front
                windshield of all motor vehicles/cycles being cited in violation of
                traffic and parking rules and regulations This will serve as proof of
                legal delivery of the violation ticket. Non-receipt of the parking
                and traffic violation does not invalidate the issuance of the
                violation ticket A photo can be taken by the guard on
                duty/personnel of the violation committed (ex. double parking)
                and violation ticket placed on the vehicle.</p>

                <h4 id="section21">Section 21. Appeal Process for Issued Violation Ticket</h4>
                <p>Drivers who receive a violation ticket have the right to appeal. The
                written appeal must be submitted to the Office of the Dean/Director
                or VPAF or VPAA where the vehicle is registered within 10 working
                days from issuance of violation ticket.</p>

                <p style="font-style: italic";>The following information must be included in the appeal.</p>
                <p>(a) Name of Appealing Party<br>
                (b) Reason for appeal<br>
                (c) Plate number of the vehicle<br>
                (d) Copy of violation ticket<br>
                (e) Appealing party's address and contact number</p>
                <p style="font-style: italic";>A copy of the resolution of appeal should be fumished to the SSU/Guard<br>
                an duty for records purposes</p>

                <h4 id="section22">Section 22. Fines to be imposed in cases of violations.</h4>
                <p>Those issued with violation tickets who opted not to appeal and
                those whose appeal were denied are obligated to pay the
                corresponding fines immediately at the designated Cashier's Office:</p>

                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; text-align: left;">
                    <thead>
                        <tr>
                            <th style="color: #09b3e4;">Parking Violation</th>
                            <th style="color: #09b3e4;">Corresponding Fee</th>
                        </tr>
                    </thead>
                    <tbody style="color: #566a7f">
                        <tr>
                            <td>Unauthorized parking/overnight parking on campus.</td>
                            <td>P 1000.00</td>
                        </tr>
                        <tr>
                            <td>Parking at a NO parking zone.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Parking on sidewalk and driveway.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Parking in reserved areas.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Improperly parked vehicle.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <th colspan="2" style="color: #09b3e4;">Traffic Violation</th>
                        </tr>
                        <tr>
                            <td>Reckless driving/ Driving in a manner that is threatening to the safety of a person.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Violation of speed limit.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Failure to give way to pedestrian on pedestrian lanes.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Disregarding traffic sign or traffic enforcers/guard on duty.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Driving vehicle on sidewalk & pedestrian walkway.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Obstructing traffic: driving against the traffic or blocking traffic.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Loading or unloading at NO loading/unloading zones.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Driving under the influence of prohibited substances.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Discourtesy/verbal abuse towards other drivers, students, pedestrians, or traffic enforcers/guard on duty.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Vandalism, removal or defacement of street signs.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Counter flowing.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Blowing of horns.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <th colspan="2" style="color: #09b3e4;">Other Violation</th>
                        </tr>
                        <tr>
                            <td>Fake/altered vehicle sticker.</td>
                            <td>P 500.00</td>
                        </tr>
                        <tr>
                            <td>Improper/unauthorized use of vehicle sticker.</td>
                            <td>P 500.00</td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h3>ENFORCEMENT REGULATION</h3>

                <h4 id="section23">Section 23. Consequence of Failure to Pay Fine</h4>
                <p>A driver-violator who refuses to pay the fines prescribed in these
                guidelines, for the first offense, shall not be allowed to bring their
                vehicles inside the University premises for a period of 2 months;
                and 6 months for the second offense.</p>

                <h4 id="section24">Section 24. Consequence of Repeated Violations of these Guidelines</h4>
                <p>A driver who violates the provisions of these guidelines for the
                third time shall no longer be allowed to bring their vehicles in
                the University premises.</p>

                <h4 id="section25">Section 25. Accounting and Utilization of Collected Fines</h4>
                <p>The amount of fines to be collected as a consequence of any
                violation of these guidelines shall be deposited on a trust fund.
                Accumulated amount of fines shall be used exclusively for the
                improvement of motorpool and security services of the
                concerned college/unit.</p>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>

    <script>
        let currentMatchIndex = 0;
        let matches = [];

        function searchGuidelines() {
            const searchInputElement = document.getElementById('sectionSearch');
            const searchInput = searchInputElement.value.trim().toLowerCase();
            const guidelineSections = document.querySelectorAll('.box-g-2 h4, .box-g-2 p');

            // Set the width of the search input to 200px
            searchInputElement.style.width = '200px';

            // Reset previous match highlights
            resetHighlights();

            if (searchInput === "") {
                matches = [];
                document.getElementById('matchInfo').innerText = '';
                searchInputElement.style.width = ''; // Reset width when input is cleared
                return;
            }

            // Find matches
            matches = Array.from(guidelineSections).filter(section => {
                const sectionText = section.innerText.toLowerCase();
                return sectionText.includes(searchInput);
            });

            // Highlight matches
            matches.forEach((match, index) => {
                highlightMatch(match, searchInput);
            });

            if (matches.length > 0) {
                currentMatchIndex = 0;
                scrollToMatch(matches[currentMatchIndex]);
                updateMatchInfo();
            } else {
                document.getElementById('matchInfo').innerText = 'No matches found.';
            }
        }

        function highlightMatch(element, searchTerm) {
            const innerHTML = element.innerHTML;
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            element.innerHTML = innerHTML.replace(regex, '<span class="highlight">$1</span>');
        }

        function resetHighlights() {
            const guidelineSections = document.querySelectorAll('.box-g-2 h4, .box-g-2 p');
            guidelineSections.forEach(section => {
                section.innerHTML = section.innerText;  // Reset content to plain text without highlights
            });

            // Remove the 'current-match' class from any previously highlighted matches
            matches.forEach(match => match.classList.remove('current-match'));
        }

        function scrollToMatch(match) {
            // Remove 'current-match' class from all matches
            matches.forEach(m => m.classList.remove('current-match'));

            // Add 'current-match' class to the new match
            match.classList.add('current-match');
            match.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function updateMatchInfo() {
            document.getElementById('matchInfo').innerText = `Match ${currentMatchIndex + 1} of ${matches.length}`;
        }

        function prevMatch() {
            if (matches.length === 0) return;

            if (currentMatchIndex > 0) {
                currentMatchIndex--;
                scrollToMatch(matches[currentMatchIndex]);
                updateMatchInfo();
            }
        }

        function nextMatch() {
            if (matches.length === 0) return;

            if (currentMatchIndex < matches.length - 1) {
                currentMatchIndex++;
                scrollToMatch(matches[currentMatchIndex]);
                updateMatchInfo();
            }
        }
    </script>
</body>
</html>
