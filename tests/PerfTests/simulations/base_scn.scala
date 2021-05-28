/*
 * Copyright 2011-2018 GatlingCorp (https://gatling.io)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package dunkomatic_base

import io.gatling.core.Predef._
import io.gatling.http.Predef._
import scala.concurrent.duration._

class BaseScn extends Simulation {

    val base_url = "http://dunkomatic_next.test"
    // val base_url = "http://h2941512.stratoserver.net"

    val httpProtocol = http
        .baseUrl(base_url) // Here is the root for all relative URLs
        .acceptHeader("text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8") // Here are the common headers
        .acceptEncodingHeader("gzip, deflate, br")
        .acceptLanguageHeader("id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7")
        .doNotTrackHeader("1")
        .userAgentHeader("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36")

    val header_nonauthorize = Map(
        "Content-Type" -> "application/x-www-form-urlencoded",
        "User-Agent" -> "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0",
        "Accept" -> "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Accept-Language" -> "fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3",
        "Connection" -> "keep-alive"
    ) // Note the headers specific to a given request

    val header_authorized = Map(
            "Accept" -> "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "XSRF-TOKEN" -> "${csrf_token}",
            "dunkomatic_next_session" -> "${csrf_token}",
        )

    object AdminLogin {
        val adminlogin = exec(http("Get Login")
            .get("/de/login")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
        )
        .pause(3.seconds, 4.seconds) // Note that Gatling has recorded real time pauses
        .exec(http("Post Admin Login") // Here's an example of a POST request
            .post("/de/login")
            .headers(header_authorized)
            .formParam("_token", "${csrf_token}") // Note the triple double quotes: used in Scala for protecting a whole chain of characters (no need for backslash)
            .formParam("email", "region@gmail.com") // Note the triple double quotes: used in Scala for protecting a whole chain of characters (no need for backslash)
            .formParam("password", "password")
            .check(status.is(200))
        )
    }
        // .exec { session => println(session("csrf_token").as[String]); session}

    object UserLogin {
        val userlogin = exec(http("Get Login")
            .get("/de/login")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
        )
        .pause(2.seconds, 10.seconds) // Note that Gatling has recorded real time pauses
        .exec(http("Post User Login") // Here's an example of a POST request
            .post("/de/login")
            .headers(header_authorized)
            .formParam("_token", "${csrf_token}")
            .formParam("email", "user@gmail.com")
            .formParam("password", "password")
            .check(status.is(200))
        )
    }

    object Logout {
        val logout = exec(http("Post logout")
            .post("/de/logout")
            .headers(header_authorized)
            .formParam("_token", "${csrf_token}")
            .check(status.is(200))
            )
    }

    object ClubList {
        val r = scala.util.Random
        val rClub = r.between(1,40)
        val rTeam = r.between(1,216)

        val clublist = exec(http("Get ClubStatistics")
            .get("/de/club/index_stats")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
            )
            .pause(2.seconds, 10.seconds)
            .exec(http("Get ClubList")
            .get("/de/club")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
            )
            .pause(2.seconds, 6.seconds)
            .exec(http("Get ClubDashboard")
            .get(s"/de/club/$rClub/dashboard")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
            )
            .pause(2.seconds, 6.seconds)
            .exec(http("Get Team")
            .get(s"/de/team/$rTeam/edit")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(status.is(200))
            )
    }

    object MemberUpdate {
        val r = scala.util.Random
        val rClub = r.between(1,40)
        val rMember = rClub * 4 + r.between(2,5)

        val memberupd = exec(http("Edit Member")
            .get(s"/de/membership/club/$rClub/member/$rMember")
            .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
            .check(css("input[type=text id=firstname]", "value").saveAs("firstname"))
            .check(css("input[type=text id=email1]", "value").saveAs("email1"))
            .check(status.is(200))
            )
            .pause(2.seconds, 5.seconds)
            .exec(http("Update Member")
            .put(s"/member/$rMember")
            .formParam("_token", "${csrf_token}")
            .formParam("firstname", "${firstname}")
            .formParam("lastname", "lastname")
            .formParam("street", "street")
            .formParam("zipcode", "zipcode")
            .formParam("city", "city")
            .formParam("mobile", "mobile")
            .formParam("phone1", "phone1")
            .formParam("email1", "${email1}")
            .check(status.is(200))
        )
    }

    object LeagueList {
        val r = scala.util.Random
        val rLeague = r.between(1,20)

        val leaguelist = exec(http("Get LeagueStatistics")
                .get("/de/league/index_stats")
                .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
                .check(status.is(200))
            )
            .pause(2.seconds, 10.seconds)
            .exec(http("Get LeagueList")
                .get("/de/league/index_stats")
                .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
                .check(status.is(200))
            )
            .pause(2.seconds, 10.seconds)
            .exec(http("Get LeagueDashboard")
                .get(s"/de/league/$rLeague/dashboard")
                .check(css("input[type=hidden name=_token]", "value").saveAs("csrf_token"))
                .check(status.is(200))
            )
    }

    val clubs = scenario("Clubs").exec(UserLogin.userlogin, ClubList.clublist, MemberUpdate.memberupd, Logout.logout)
    val leagues = scenario("Leagues").exec(UserLogin.userlogin, LeagueList.leaguelist, Logout.logout)
    val admins = scenario("Admins").exec(AdminLogin.adminlogin, LeagueList.leaguelist, ClubList.clublist, Logout.logout)

}
