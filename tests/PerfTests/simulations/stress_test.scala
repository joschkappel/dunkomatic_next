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

package dunkomatic_soak

import io.gatling.core.Predef._
import io.gatling.http.Predef._
import scala.concurrent.duration._
import dunkomatic_base.BaseScn

class StressTetst extends BaseScn {

    setUp(
        admins.inject(rampUsers(4) during(20.seconds)),
        clubs.inject(rampUsers(50) during(2.minutes)),
        leagues.inject(rampUsers(40) during(2.minutes)),
        ).protocols(httpProtocol)

    // nothingFor(duration): Pause for a given duration.
    // atOnceUsers(nbUsers): Injects a given number of users at once.
    // rampUsers(nbUsers) during(duration): Injects a given number of users with a linear ramp over a given duration.
    // constantUsersPerSec(rate) during(duration): Injects users at a constant rate, defined in users per second, during a given duration. Users will be injected at regular intervals.
    // constantUsersPerSec(rate) during(duration) randomized: Injects users at a constant rate, defined in users per second, during a given duration. Users will be injected at randomized intervals.
    // rampUsersPerSec(rate1) to (rate2) during(duration): Injects users from starting rate to target rate, defined in users per second, during a given duration. Users will be injected at regular intervals.
    // rampUsersPerSec(rate1) to(rate2) during(duration) randomized: Injects users from starting rate to target rate, defined in users per second, during a given duration. Users will be injected at randomized intervals.
    // heavisideUsers(nbUsers) during(duration): Injects a given number of users following a smooth approximation of the heaviside step function stretched to a given duration.
    // reachRps(target) in (duration): target a throughput with a ramp over a given duration.
    // jumpToRps(target): jump immediately to a given targeted throughput.
    // holdFor(duration): hold the current throughput for a given duration.
}