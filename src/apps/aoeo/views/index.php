<div id="indexdiv">


    <div id="mainsearch">
        <img src="/images/AvatarLibrary.png" alt="logo"/><br/>
        <p id="mainpagetext">
            <a href="/items">Items</a> | <a href="/units">Units</a> | <a href="/advisors">Advisors</a> | <a
                href="/blueprints">Blueprints</a> | <a href="/materials">Materials</a> | <a href="/designs">Recipes</a>
            | <a href="/consumables">Consumables</a>
        </p>
        <div id="searchdiv">
            <form name="search" action="/aoeo/search" method="post">
                <input id="query" type="text" name="q"/><input type="image" src="/images/spyglass.png"/>
            </form>
        </div>
        <br/>

        <div class="tooltip" style="width: 500px; margin-left: auto; margin-right: auto;">
            <div class="inside" id="news">

                <h1>06/27/17: Project Celeste</h1>
         
                <img src="/images/Art/UserInterface/Icons/Equipment/FishingNet1H_L001_ua.png" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>
                 </br>It's good to be back.<br/><br/></br>
                </p>

                <h1>08/14/12: Patch Update</h1>
                <img src="/images/Art/UserInterface/Icons/Equipment/Vanity/102_ElGourdo_Gear_ua.png" width="64px"
                     alt="newsicon" style="float:left;margin:0 5px 0 0;"/>
                <p>Updated to Anniversary Patch! As usual artwork is taking up a bit of time to upload but will be
                    completely up tonight. We're excited about the anniversary update as it introduces a whole new civ
                    and lots of new quests. As usual if you have any questions/comments/concerns contact us!
                    <br/> It's no longer Dave's fault.</p>

                <h1>03/27/12: Patch Update</h1>
                <img src="/images/Art/UserInterface/Icons/Units/AvatarCDruid_ua.png" width="64px" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>Updated to Spring Patch. Artwork still not up but will be up later tonight. <b>We are experiencing a
                    very heavy load right now </b> - we're trying to do some optimizations and upgrades to handle the
                    traffic, please be patient.
                    <br/> It's all Dave's fault.</p>

                <?php /*
                <h1>01/11/12: Patch Update</h1>
                <img src="/images/Art/UserInterface/Icons/Consumable/ConBandEliteCav4_ua.png" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>Updated to build 5582. Not much new it seems, just some advisors.<br/><br/><br/></p>


                <h1>06/26/17: Project Celeste</h1>
                <p>It has been 6 years since our last update? Welp I'm old.
                    <br/><br/>
                    - Luciano "Elpea"</p>

                <h1>11/16/11: Update for patch (build 5539)</h1>
                <img src="/images/Art/UserInterface/Icons/Consumable/ConBandEliteCav4_ua.png" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>We've updated all the stats to the new build that is coming out tomorrow. Not sure if many or if any
                    changes have been made, but all stats should be up-to-date. It should be noted that Champion Mode
                    stats are
                    different than quest stats and we do not display champion mode stats yet.
                    <br/><br/>
                    - Luciano "Elpea"</p>

                <h1>11/06/11: Advisors, Recipes, Consumables, assorted improvements</h1>
                <img src="/images/Art/UserInterface/Icons/Crafting/CraftingVillagers4_ua.png" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>It's been about 10 days since we last released an update, but we've still been productive despite
                    "real-life" eating away at our time. We've added consumables, recipes, and advisors. We've been
                    apprehensive about releasing information on the Celts, but since many of our users have already
                    figured out how to access that information anyways (and it's available in a text file in your
                    installation directory), we've gone ahead and made the links official.</p>

                <p>Although Dave and I have been mostly focusing on backend things and parsing new data, we realize that
                    our layout hasn't been the most amazing. We've made many improvements to our tables, and while
                    sorting isn't implemented yet, we think our current version is a big improvement over what we had
                    before.</p>

                <p>Finally, we're using an in-game loading screen as our background. Exploration and the search for
                    information is sort of a theme for our site, so we thought the Egyptian ship at sunrise (Dave
                    figured this out based on the direction the ship is traveling, crazy sailers) was appropriate. As
                    with everything else, this beautiful artwork belongs to Microsoft.
                    <br/><br/>
                    - Luciano "Elpea"</p>
                <?/*
<h1>10/27/11: Materials, blueprints, and improved search!</h1>
                <img src="/images/Art/UserInterface/Icons/Materials/MatIconsGemstones_ua.png" alt="newsicon"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>Materials and blueprints link to each other pretty extensively, and there are all kinds of fancy
                    queries to grab the information. Coming up next: <strike>my thesis</strike> crafting patterns, which
                    should be pretty straightforward (very similar to blueprints).</p>
                <p>Meanwhile, Elpea has been hard at work making our search not suck! It's actually really good now, try
                    it!
                    <br/><br/>- Dave</p>

                <h1>10/25/11: Item levels.</h1>
                <img src="/images/Art/UserInterface/Icons/Equipment/FishingNet1H_L001_ua.png"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>Items now have their available levels listed, as pulled from the Empire Handbook. Items which do not
                    have data (including legendaries) are set to level 40. Of course, you can still choose any level you
                    want with the slider. And now I sleep. <br/> <br/> - Luciano "Elpea"</p>

                <h1>10/24/11: Fixed issues with damage bonuses.</h1>
                <img src="/images/Art/UserInterface/Icons/Units/Spearman64_ua.png"
                     style="float:left;margin:0 5px 0 0;"/>
                <p>We've fixed the issue with units having multiple damage bonuses only showing one. Thank you everyone
                    who reported it in!</p>
                <p> Dave and I are currently trying to iron out the bugs with this version but we've got all sorts of
                    tools coming up. More importantly, the rest of the in-game items are being added (blueprints
                    materials, consumables, advisors) to our database and as soon as we finish up some fixing we'll have
                    them up.<br/> <br/> - Luciano "Elpea"</p>
                */?>
            </div>

            <?php make_tooltip(); ?>
        </div>

        <div id="credits">&copy; 2017 <a
                href="https://ageofempires.guide">Age of Empires Online Database</a>.
            Not affiliated<br/>
            with <a href="http://www.microsoft.com/games/">Microsoft Studios</a>, <a href="http://gaspowered.com/">Gas
                Powered Games</a>, or <a href="http://www.robotentertainment.com/">Robot Entertainment</a>.<br/>
            Written by Luciano Pereira and David Casagrande with much love, sweat, and tears. <br/>
        </div>
    </div>
