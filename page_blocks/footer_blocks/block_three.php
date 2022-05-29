<?php

$block_three =  <<<BLOCK_THREE

<div class="col-xs-12">
    <h5 align="center">Let's Talk Mortality</h5>
    <hr>
    <p>
    We all see data presented on the mortality rate. The net mortality rate is a fairly simple calulation to do, under the guise that the data in and of itself is at all trustworthy. You simply take deaths divided by cases times 100. There's your overall infection mortality rate(IFR). Not rocket science.
    </p>
    <p>
    If we wanted to plot on a timeline the mortality rate we would need a case fality rate(CFR) - this is extremely tricky 'slash' impossible. Here we would need to be able to match every confirmed death of every single individual, and track that individuals matracies such as:
    <ol>
        <li>Did the person actually test positive for COVID, or was this a 'named' fatality based upon symptoms?</li>
        <li>When was the first onset of symptoms?</li>
        <li>What is the time from diagnosis to death?</li>
    </ol>
    For each individual... this is an impossible task. Not only in terms of the potential volume of data, but also there are massive variabilities with regards to the quality of data that could be reported. Further still there are strong privacy rights to consider. It's only reasonable to estimate the CFR - which is what I have done in my analysis.
    </p>
    <p>
        Based upon the following studies I am using an estimate length of time from diagnosis to death: <strong>19 days from infection to death</strong>, and I am applying it for every country. If alarm bells aren't ringing for you, then you need to start to think more criticially.  Doing this is VERY problmematic: different countries with different helathcare standards and different demographics etc, etc, etc - will not have the same rate of demise of a patient.  I know this and any analysis of a CFR, from anyone, is equally contentious. Take the CFR with a grain of salt        
    </p>
    <p>
    Further still, as with both the case data - I am using the trending 15 day average figure plus asymptomatic for the cases. I am sure there will be some of you who will say, "Adding asymptomatic? Trending? this guy's playing with data to support his optinion!" - If I hadn't disclosed what I was doing, how I was doing andwhy I was doing it - I'd agree with you. But since I am telling you those things - I don't think your argument holds water.
    <ul>
        <li><a href="https://www.thelancet.com/journals/lancet/article/PIIS0140-6736(20)30566-3/fulltext" target="new" style="text-decoration:underline; color:rgba(107, 77, 112, 0.99);">18.5 Days - March 20th, 2020 (CN)</a></li>
        <li><a href="https://assets.publishing.service.gov.uk/government/uploads/system/uploads/attachment_data/file/928729/S0803_CO-CIN_-_Time_from_symptom_onset_until_death.pdf" target="new" style="text-decoration:underline; color:rgba(107, 77, 112, 0.99);">7 to 13 Days - Oct 7th, 2020 (UK)</a></li>
        <li><a href="https://cdn1.sph.harvard.edu/wp-content/uploads/sites/1266/2020/07/HCPDS-WP_19_4_testa-et-al_Visualizing-Lagged-Connection-Between-COVID-19-Cases-and-Deaths-in-US_final_07_10_with-cover.pdf" target="new" style="text-decoration:underline; color:rgba(107, 77, 112, 0.99);">14 to 56 Days - July 10th, 2020 (US via CN)</a></li>
        <li><a href="https://www.ncbi.nlm.nih.gov/pmc/articles/PMC7589278/" target="new" style="text-decoration:underline; color:rgba(107, 77, 112, 0.99);">25 Days - Oct 17th, 2020 (BE)</a></li>
        <li><a href="https://www.folkhalsomyndigheten.se/contentassets/53c0dc391be54f5d959ead9131edb771/infection-fatality-rate-covid-19-stockholm-technical-report.pdf" target="new" style="text-decoration:underline; color:rgba(107, 77, 112, 0.99);">Sweden's Infection Fatality Rate</a></li>

    </ul>
    
    </p>
</div>

BLOCK_THREE;
