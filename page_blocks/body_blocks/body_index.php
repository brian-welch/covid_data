<?php

$page_name = new Tag('h1','DATA: Only as reliable as its source<hr>',['id'=>'page_name']);

$image_tag = new Image('images/virus_image_conceptual_02.jpg','Digital Representation of a Virus');

$image_column = new Tag('div', '',['class'=>'col-lg-5 d-none d-lg-flex flex-column virus-hero']);

$image_hero_small_screen = new Tag('div', $image_tag->get_html(),['class'=>'col-xs-12 d-xs-block d-lg-none']);
$image_hero_small_screen->set_attribute('style','margin-bottom:20px;max-height:150px;display:flex;justify-content:center;align-items:center;');

$accordion_copy_01 = [
    "The Why's"=>"Too many people, have too little access to the facts & data about COVID. This site is designed to one end: for everyone to see the data for themselves. The data has no inherit judgement and has no ajenda. Once an individual has the data - he or she is free to decide for themselves. And therein lies the rub: one person's value judgement might say 100 cases per million is way to high, and another person might say it's not that bad.<br><br><strong>And remember: values are opinions - not absolute facts.</strong><br><br>If you don't agree with another person's value assessment, you have zero grounds to claim that the other person is 'wrong'. You can say that 'I think you're wrong', or 'I disagree', but you are not ethically permitted to say in absolute terms that the persom whom which you disagree with is 'wrong'.",
    "The What's"=>"What this IS, is a collection of graphs and charts which will provide you with a visual summary of data and help for you quickly grasp the reality of the situation in all its shame and its glory. All raw source data is referenced.<br><br>What this is NOT, is some echo-chamber of confirmation bias intended to push you one way or another on what constitutes a success or who has won and who has lost. I may feel one way or the other on COVID, but my only goal is the presentation of the facts and the data.",
    "The How's"=>"The source of the data is second hand and cited. I trust that the raw data presented here was sourced from a reasonable source, which in turn cites its source data. The data, for the purpose of analysis, has been aggragated and presented on a per capita scale - for this is the one and only way to assess a countries relative situation in comparison to others' situation.<br><br>Furthermore, most of the data is being presented with 'trending' to make the data easier on the eyes and to account for individual variability and reporting irregularities. 'Trending' does not mean any prognostications are being made - no predictive modelling is included in this site.",
    "The Who's"=>"Not the Whos from Whoville and not the World Health [dis]Organization. Certain countries or other sovereign states have been elimnated from the data presented for 1 of 3 reasons: <ul><li>either a 'state' suffers from <stonrg><em>OUTSTANDING</em></strong> credibility issues</li><li>or a state was deemed 'secondary' and lacking merit</li><li>sourcing some key data points was contentious or unavailable</li></ul>Examples would include China and North Korea and Vatican City and numerous, smaller island nations. And while all data includes a level of uncertainty or error rate - and some politically mindless countries are more likely to be prone to have more eroneous reporting methods in order to push a political end - some sovereigns are more vile than others and ought not be considered to have any veracity whatsoever.<br><br>Not all excluded nation states should be seen as a slight - though some very much are. Particularly in the case of smaller island nations, some source data may have amalgamated island states into groups or clusters. So for this reason there are some missing countries - mea culpa.",
];

function get_accordion_item_html($id, $index, $key, $value) {
    return  <<<TEMP
        <div class="accordion-item"><h2 class="accordion-header" id="heading{$index}"><a href='#accordianMarker'><button class="accordion-button element-contrasted collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{$index}" aria-expanded="false" aria-controls="collapse_{$index}" onclick="this.blur()" >{$key}</button></a></h2><div id="collapse_{$index}" class="accordion-collapse collapse" aria-labelledby="heading{$index}" data-bs-parent="#accordion_{$id}"><div class="accordion-body">{$value}</div></div></div>
TEMP;
}

function build_accordion_block($accordion_array, $id, $column_classes) {
    $index = 1;
    $temp = "<div class='accordion {$column_classes}' id='accordion_{$id}'>";
    foreach ($accordion_array as $key => $value) {
        $temp .= get_accordion_item_html($id, $index, $key, $value);
        $index++;
    }
    $temp .= "</div>";
    return $temp;
}

$preface_stmt_inner = "
    <div class='row'><div class='col-xs-12 col-md-6'>
        <h2>Preface:</h2>
        <p>It is important to realize that these graphs and the eventual value judgments &amp; opinions which you are likely to distil from the data are at the whim of the source data providors. Shit in is shit out. By looking at the agregate, we can hope to actually 'miss the trees for the forest'; to turn the old adage on its head. But this by no means a guarantee that errors will be mitigated over gross data sets.</p>
        <p>Example: Do you feel as though it is reasonable to declare the cause of death as COVID if the patient was never tested? This is a very probmomatic methodology, to put it mildly.</p>
        <p>And while it is slightly outside the purview of the main goal of this site, in time there will also be data with respect to vaccination against COVID.</p>
    </div><div class='col-xs-12 col-md-6'>
        <h2>Important Quote:</h2>
        <figure>
            <blockquote cite=''>
                <p>And now we have found, you know, after 6 or 7 years of doing this, over 100 new SARS-related coronaviruses, some very close to SARS .... Some of them get into human cells in the lab, some of them can cause SARS disease in humanized mice models and are untreatable with theraputic monoclonals and you can't vaccinate against them with a vaccine.</p>
            </blockquote>
            <figcaption>—Peter Daszak, <strong>Dec 9, 2019</strong>, <cite>Preseident of Eco Health Aliance</cite><br><cite>FUNDED Gain of Function Research at the Wuhan Institute of Virology</cite></figcaption>
        </figure>
    </div></div>
    <div id='accordianMarker'>
        <hr>
    </div>
";

$preface_stmt_inner_obj = new Tag('div',$preface_stmt_inner, ['class'=>'col-xs-12 col-lg-7'], false);
$preface_stmt_outer_obj = new Tag('div', $preface_stmt_inner_obj->get_html(),['class'=>'row']);

// ====================================

echo "<div class='container'>";

$page_name->echo_html();

echo "<div class='row' id='renderArea'>";

$image_hero_small_screen->echo_html();

echo $preface_stmt_inner;

echo build_accordion_block($accordion_copy_01, "Preface", "col-xs-12 col-lg-7");
// $preface_stmt_inner_obj->echo_html();

$image_column->echo_html();

echo "</div><!--End Render Area-->";
echo "</div><!--End container-->";
