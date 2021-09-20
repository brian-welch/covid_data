<?php
    include 'lib/function_index.php';
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>COVID Data Presentation</title>
        <meta name="description" content="An amateur project with the intent to present statistical data related to infections and death.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css?<?php echo date("YmdHis");?>">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>My COVID Data Presentation</h1>
            </header>
            <hr/>
            <hr/>
            <div class="row justify-content-center">
            <div class="preface col-xs-12 col-lg-9">
            <div class="alert alert-primary " role="alert"><h2>Everybody Center Yourself</h2></div>
                <p>
                Before you, as the independant viewer of this content, try to label me - or more importantly <strong>YOURSELF</strong> - as one thing or another, I'd ask that you try to refrain from tribalism.
                </p>
                <p>
                I ask that you do the exact <strong>opposite</strong> that FOX or CNN or News Max or MSNBC or Sky News Australia or RT or <strong><code>[any other msm outlet]</code></strong> would have you do. And that which they would have you do is that you label each other; that you mock each other; that you hate each other; that you side up; that you entrench.
                </p>
                <hr>
                <p>
                    <h4>Here are some of the precepts that <strong>I</strong> wish that you agree to:</h4>
                    <ul>
                        <li>
                        I, as the publisher of this data and it's graphical representation, am an aggregated centerist in my leanings. Some worldly things I might trend towards one side of the group spectrum and on other things I may trend towards the other side of the spectrum. Ergo: I 'am' no political party; I 'am' no specific adjective. Cogito, ergo sum.
                        </li>
                        <li>
                        Just because there may be data represented here which supports one politician's public claim, does by no means guarantee that I agree with that hypothetical politician - In point of fact - it's very possible that I personally may think that the hypothetical politician is a deranged, psociopathic circus-clown pedofile.
                        </li>
                        <li>
                        I may present questions throughout this page which are designed to initiate self reflection, but I will refrain from offering forward my own answers. As such, you ought to refrain from pretending to know what I think, or feel, or what I might expect for you to answer.
                        </li>
                        <li>
                        I accept the reality that <span class="covid-word-emphasis">COVID</span> is real; microchips in vaccines are very much not.
                        </li>
                        <li>
                        The data represented here is only as good as the source and the methodology for collectiong said data. It is for each person to decide the veracity.
                        </li>
                    </ul>
                </p>
            </div>
            <div class="preface col-xs-12 col-lg-9">
                <div class="alert alert-primary " role="alert"><h2>Full Disclosure</h2></div>

                    <p>
                        <ul>
                            <li>
                            Data has been presented, in large part, using representative calculations in order to be proportionally representative.
                            </li>
                            <li>
                            While not a bullet proof meathod to assess relative severity of <span class="covid-word-emphasis">COVID</span> infection, representative does make it significantly more egalitarian. 
                            </li>
                            <li>
                        Daily data of cases and deaths are averaged aver a 15 day period to:
                                <ul>
                                    <li>attempt to accommodate for variations in reporting times and variations in the progression of the infection between individuals</li>
                                    <li>make the data more readable and show trends</li>
                                </ul>
                            </li>
                            <li>
                            When looking into information of how many in a population could be completely asymptomatic, several studies and articles were looked at to come up with an estimated average. At the time of this publication, I saw articles with a total span of 20% to 50%. Averaging out all the examples I found, I am using 32.6% as an estimated percent of the population who are 100% asymptomatic yet infected with <span class="covid-word-emphasis">SARS CoV-2</span>.
                            </li>
                        </ul>
                    </p>
                    <hr>
                    <h4>Without Further Ado.....</h4>
                    <hr>
                </div>
            </div>
            <div class="cases-contianer">
                <h3>Data: Daily Cases Per 1,000,000 People</h3>
                <div class="row data-matrix-group justify-content-center">
                    
                    <?php
                    render_all_country_cases($all_countries_data);
                    ?>

                </div>
            </div>

        </div>
        <footer>
            <div class="row">
                <div class="col-xs-12 col-s-6 col-lg-4">
                    <h5>Left Footer Column</h5>
                    <ul>
                        <li> One </li>
                        <li> Two </li>
                        <li> Three </li>
                        <li> Four </li>
                        <li> Five </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-s-6 col-lg-4">
                    <h5>Center Footer Column</h5>
                    <ul>
                        <li> One </li>
                        <li> Two </li>
                        <li> Three </li>
                        <li> Four </li>
                        <li> Five </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-s-6 col-lg-4">
                    <h5>Right Footer Column</h5>
                    <ul>
                        <li> One </li>
                        <li> Two </li>
                        <li> Three </li>
                        <li> Four </li>
                        <li> Five </li>
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>
