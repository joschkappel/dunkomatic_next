SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `team_no`, `league_id`, `club_id`, `league_char`, `league_no`, `preferred_league_char`, `preferred_league_no`, `league_prev`, `training_day`, `training_time`, `preferred_game_day`, `preferred_game_time`, `shirt_color`, `coach_name`, `coach_phone1`, `coach_phone2`, `coach_email`, `changeable`, `created_at`, `updated_at`, `gym_id`) VALUES
(1, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, 2, '02:30:00', 2, '08:00:00', 'DeepSkyBlue', 'Robert Krauß', '08451166672', NULL, 'clauspeter.geiger@hamann.org', 1, '2022-09-02 14:19:33', '2022-09-02 14:19:33', 1),
(2, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, 5, '20:30:00', 2, '13:00:00', 'White', 'Herr Prof. Hagen Günther', '09652 867850', NULL, 'baum.hansjosef@gmx.de', 1, '2022-09-02 14:19:33', '2022-09-02 14:19:33', 2),
(3, 7, NULL, 3, NULL, NULL, NULL, NULL, NULL, 4, '08:30:00', 7, '15:00:00', 'LightGoldenRodYellow', 'Frau Lilli Baum B.Eng.', '+49 (0) 7506 058011', NULL, 'heinz.klausjurgen@lechner.de', 1, '2022-09-02 14:19:33', '2022-09-02 14:19:33', 3),
(4, 7, NULL, 4, NULL, NULL, NULL, NULL, NULL, 5, '05:30:00', 5, '02:00:00', 'Aqua', 'Lilly Mayer', '07606 911092', NULL, 'beier.margrit@gmail.com', 1, '2022-09-02 14:19:33', '2022-09-02 14:19:33', 4),
(5, 8, NULL, 5, NULL, NULL, NULL, NULL, NULL, 1, '03:30:00', 4, '07:00:00', 'Chocolate', 'Sabrina Miller', '+49(0)0387 41950', NULL, 'berger.lieselotte@live.de', 1, '2022-09-02 14:19:33', '2022-09-02 14:19:33', 5),
(6, 4, NULL, 6, NULL, NULL, NULL, NULL, NULL, 3, '05:30:00', 4, '21:00:00', 'Lime', 'Kevin Fröhlich', '+49(0)5573 33971', NULL, 'qkraus@yahoo.de', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 6),
(7, 8, NULL, 7, NULL, NULL, NULL, NULL, NULL, 4, '19:30:00', 5, '08:00:00', 'DimGrey', 'Kurt Mack B.A.', '+49(0)0345 09781', NULL, 'ewild@reimer.org', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 7),
(8, 3, NULL, 8, NULL, NULL, NULL, NULL, NULL, 2, '22:30:00', 4, '06:00:00', 'SpringGreen', 'Lorenz Blum', '(04741) 702069', NULL, 'skohl@schott.com', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 8),
(9, 3, NULL, 9, NULL, NULL, NULL, NULL, NULL, 1, '08:30:00', 1, '19:00:00', 'Chartreuse', 'Svetlana Bode', '09080266020', NULL, 'kessler.hannes@brenner.de', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 9),
(10, 9, NULL, 10, NULL, NULL, NULL, NULL, NULL, 4, '06:30:00', 5, '04:00:00', 'OliveDrab', 'Marija Kretschmer', '+49(0) 256941541', NULL, 'hinz.heiderose@baumgartner.com', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 10),
(11, 8, NULL, 11, NULL, NULL, NULL, NULL, NULL, 3, '21:30:00', 4, '15:00:00', 'DarkGreen', 'Herr Frank Riedl', '+49(0) 278851919', NULL, 'kfritsch@decker.de', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 11),
(12, 6, NULL, 12, NULL, NULL, NULL, NULL, NULL, 5, '20:30:00', 2, '23:00:00', 'SlateBlue', 'Andrea Voss B.Eng.', '(07871) 53945', NULL, 'jacqueline21@merkel.org', 1, '2022-09-02 14:19:34', '2022-09-02 14:19:34', 12),
(13, 5, NULL, 13, NULL, NULL, NULL, NULL, NULL, 4, '09:30:00', 7, '10:00:00', 'SeaShell', 'August Stoll', '+49(0)1495 926111', NULL, 'blorenz@schulze.org', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 13),
(14, 4, NULL, 14, NULL, NULL, NULL, NULL, NULL, 3, '20:30:00', 6, '05:00:00', 'SkyBlue', 'Irma Wilke B.A.', '02763829149', NULL, 'bernadette.kiefer@albert.com', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 14),
(15, 3, NULL, 15, NULL, NULL, NULL, NULL, NULL, 2, '09:30:00', 7, '15:00:00', 'HoneyDew', 'Ivan Heck', '0506534140', NULL, 'ekkehard40@mail.de', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 15),
(16, 9, NULL, 16, NULL, NULL, NULL, NULL, NULL, 5, '10:30:00', 6, '05:00:00', 'LightSkyBlue', 'Juliane Oswald', '04932 65107', NULL, 'peer.bartels@will.de', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 16),
(17, 7, NULL, 17, NULL, NULL, NULL, NULL, NULL, 2, '17:30:00', 6, '10:00:00', 'DarkSalmon', 'Heinz Bach', '01972 186514', NULL, 'gwegner@stumpf.de', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 17),
(18, 4, NULL, 18, NULL, NULL, NULL, NULL, NULL, 2, '12:30:00', 6, '13:00:00', 'Beige', 'Elena Ebert', '(00165) 724266', NULL, 'yott@yahoo.de', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 18),
(19, 2, NULL, 19, NULL, NULL, NULL, NULL, NULL, 3, '16:30:00', 6, '19:00:00', 'Silver', 'Frieda Rauch', '(06974) 407173', NULL, 'ingeburg.schmitz@mayer.org', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 19),
(20, 8, NULL, 20, NULL, NULL, NULL, NULL, NULL, 1, '20:30:00', 2, '20:00:00', 'NavajoWhite', 'Marga Schwab-Pfeifer', '+49(0)2651718334', NULL, 'heinzpeter11@gmx.de', 1, '2022-09-02 14:19:35', '2022-09-02 14:19:35', 20),
(21, 4, 6, 21, 'A', 1, NULL, NULL, NULL, 2, '21:30:00', 7, '21:00:00', 'PaleGreen', 'Silvia Wahl', '(06615) 094397', NULL, 'udo.konrad@googlemail.com', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 21),
(22, 7, NULL, 22, NULL, NULL, NULL, NULL, NULL, 5, '00:30:00', 5, '02:00:00', 'FloralWhite', 'Hanne Gross', '(01812) 651718', NULL, 'konstantin64@wolff.de', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 22),
(23, 3, NULL, 23, NULL, NULL, NULL, NULL, NULL, 5, '13:30:00', 1, '04:00:00', 'SaddleBrown', 'Herr Marcel Meißner', '(09327) 87690', NULL, 'bruno34@gmail.com', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 23),
(24, 2, NULL, 24, NULL, NULL, NULL, NULL, NULL, 5, '12:30:00', 7, '20:00:00', 'Olive', 'Friedhelm Bär', '+49(0)9098 548016', NULL, 'mconrad@mail.de', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 24),
(25, 1, 7, 25, 'A', 1, NULL, NULL, NULL, 5, '05:30:00', 6, '03:00:00', 'DarkOrchid', 'Herr Prof. Dr. Xaver Steffens B.A.', '+49(0) 077936300', NULL, 'bahrens@gmail.com', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 25),
(26, 4, NULL, 26, NULL, NULL, NULL, NULL, NULL, 5, '15:30:00', 4, '13:00:00', 'Plum', 'Hans-J. Reuter', '+49(0)8704 00752', NULL, 'heinze.michael@zander.com', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 26),
(27, 9, NULL, 27, NULL, NULL, NULL, NULL, NULL, 2, '06:30:00', 1, '16:00:00', 'AliceBlue', 'Herr Prof. Dr. Harro Klemm', '00103 32098', NULL, 'erhard.wulf@hotmail.de', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 27),
(28, 1, NULL, 28, NULL, NULL, NULL, NULL, NULL, 2, '01:30:00', 3, '03:00:00', 'RosyBrown', 'Jessica Schaller', '01670 808006', NULL, 'franzjosef40@yahoo.de', 1, '2022-09-02 14:19:36', '2022-09-02 14:19:36', 28),
(29, 6, 8, 29, NULL, NULL, NULL, NULL, NULL, 3, '14:30:00', 6, '07:00:00', 'FloralWhite', 'Krystyna Zimmer-Weber', '(02795) 49287', NULL, 'matthias.klose@aol.de', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 29),
(30, 1, NULL, 30, NULL, NULL, NULL, NULL, NULL, 2, '18:30:00', 7, '12:00:00', 'SlateBlue', 'Albert Gerber', '+49(0) 026531430', NULL, 'winfried61@krebs.org', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 30),
(31, 9, NULL, 31, NULL, NULL, NULL, NULL, NULL, 4, '21:30:00', 2, '15:00:00', 'DarkSlateGray', 'Frau Juliane Ludwig B.Sc.', '+49(0)4264059184', NULL, 'paul.heil@web.de', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 31),
(32, 6, NULL, 32, NULL, NULL, NULL, NULL, NULL, 5, '12:30:00', 7, '02:00:00', 'GreenYellow', 'Ortrud Linke', '+49 (0) 8276 088288', NULL, 'vwunderlich@freenet.de', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 32),
(33, 3, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, '07:30:00', 5, '19:00:00', 'LimeGreen', 'Herr Fred Hiller B.Sc.', '+49(0)3926683910', NULL, 'ludmilla.schweizer@mail.de', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 33),
(34, 6, NULL, 34, NULL, NULL, NULL, NULL, NULL, 2, '22:30:00', 3, '23:00:00', 'SpringGreen', 'Heiner Christ-Sonntag', '+49(0)8302 043795', NULL, 'wolfgang64@mail.de', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:37', 34),
(35, 9, NULL, 35, NULL, NULL, NULL, NULL, NULL, 2, '20:30:00', 6, '18:00:00', 'Peru', 'Herr Prof. Dr. Nicolas Nowak', '0723301121', NULL, 'anton.mayr@gmail.com', 1, '2022-09-02 14:19:37', '2022-09-02 14:19:38', 35),
(36, 6, NULL, 36, NULL, NULL, NULL, NULL, NULL, 2, '02:30:00', 4, '00:00:00', 'MediumPurple', 'Andrej Kramer', '+49(0)6132 28660', NULL, 'gpfeifer@heinrich.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 36),
(37, 8, 10, 37, 'A', 1, NULL, NULL, NULL, 5, '00:30:00', 7, '19:00:00', 'PapayaWhip', 'Eugenie Brandl', '05532 010927', NULL, 'karlernst50@beier.org', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 37),
(38, 9, 10, 38, 'B', 2, NULL, NULL, NULL, 2, '13:30:00', 2, '23:00:00', 'SaddleBrown', 'Margarita Berg', '+49(0) 054259580', NULL, 'ayse40@mail.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 38),
(39, 1, NULL, 39, NULL, NULL, NULL, NULL, NULL, 1, '22:30:00', 3, '01:00:00', 'DarkSlateBlue', 'Domenico Rapp-Wolf', '0856761536', NULL, 'edith81@engelhardt.org', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 39),
(40, 6, NULL, 40, NULL, NULL, NULL, NULL, NULL, 3, '22:30:00', 2, '01:00:00', 'LimeGreen', 'Salvatore Dietz', '0693382272', NULL, 'marlene52@live.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 40),
(41, 8, 11, 41, 'A', 1, NULL, NULL, NULL, 2, '03:30:00', 7, '15:00:00', 'Red', 'Herr Prof. Heinz-Peter Bauer B.Eng.', '+49(0) 046768014', NULL, 'dietrich.springer@hofmann.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 41),
(42, 8, 11, 42, 'B', 2, NULL, NULL, NULL, 4, '20:30:00', 3, '14:00:00', 'OrangeRed', 'Herr Prof. Andreas Kraus', '+49(0)9999 63439', NULL, 'david60@feldmann.com', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 42),
(43, 9, NULL, 43, NULL, NULL, NULL, NULL, NULL, 2, '10:30:00', 7, '23:00:00', 'DarkOrchid', 'Falko Runge', '01381 262531', NULL, 'dieter.kuhn@yahoo.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 43),
(44, 5, NULL, 44, NULL, NULL, NULL, NULL, NULL, 2, '23:30:00', 5, '08:00:00', 'Fuchsia', 'Liesel Lenz', '03563422550', NULL, 'zwahl@posteo.de', 1, '2022-09-02 14:19:38', '2022-09-02 14:19:38', 44),
(45, 4, 12, 45, NULL, NULL, NULL, NULL, NULL, 2, '17:30:00', 6, '06:00:00', 'Moccasin', 'Ludmilla Kirchner B.A.', '(01775) 79832', NULL, 'thorsten53@gmail.com', 1, '2022-09-02 14:19:39', '2022-09-02 14:19:39', 45),
(46, 7, 12, 46, NULL, NULL, NULL, NULL, NULL, 4, '05:30:00', 1, '10:00:00', 'MintCream', 'Dietmar Walter', '+49(0)0174 218626', NULL, 'kornelia.moll@t-online.de', 1, '2022-09-02 14:19:39', '2022-09-02 14:19:39', 46),
(47, 7, NULL, 47, NULL, NULL, NULL, NULL, NULL, 4, '07:30:00', 7, '06:00:00', 'Chocolate', 'Hermann-Josef Arndt', '+49(0)4842371735', NULL, 'fforster@zimmer.com', 1, '2022-09-02 14:19:39', '2022-09-02 14:19:39', 47),
(48, 5, NULL, 48, NULL, NULL, NULL, NULL, NULL, 5, '09:30:00', 6, '17:00:00', 'LimeGreen', 'Luzia Herzog B.Sc.', '+49 (0) 6752 192382', NULL, 'beck.elfi@scholz.com', 1, '2022-09-02 14:19:39', '2022-09-02 14:19:39', 48),
(49, 4, NULL, 49, NULL, NULL, NULL, NULL, NULL, 2, '19:30:00', 7, '23:00:00', 'CornflowerBlue', 'Carlo Brandt', '08021 35495', NULL, 'armin28@runge.net', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 49),
(50, 4, NULL, 50, NULL, NULL, NULL, NULL, NULL, 1, '01:30:00', 4, '03:00:00', 'SlateGray', 'Tatjana Rudolph', '+49(0)1150 754775', NULL, 'krebs.rose@jost.com', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 50),
(51, 2, NULL, 51, NULL, NULL, NULL, NULL, NULL, 5, '14:30:00', 2, '02:00:00', 'Tan', 'Frau Danuta Wiesner', '+49(0)1337453460', NULL, 'cbeckmann@live.de', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 51),
(52, 6, NULL, 52, NULL, NULL, NULL, NULL, NULL, 4, '06:30:00', 5, '12:00:00', 'DarkMagenta', 'Frau Dr. Ingrid Bauer B.Sc.', '00251 17766', NULL, 'arthur.haag@bachmann.org', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 52),
(53, 7, 14, 53, 'A', 1, NULL, NULL, NULL, 5, '20:30:00', 7, '08:00:00', 'SaddleBrown', 'Bianka Hiller-Wirth', '0498528899', NULL, 'sauer.helga@sauter.com', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 53),
(54, 1, 14, 54, 'B', 2, NULL, NULL, NULL, 2, '16:30:00', 6, '10:00:00', 'DarkMagenta', 'Frau Prof. Hildegard Metz', '07636 374509', NULL, 'wjacobs@live.de', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 54),
(55, 7, 14, 55, 'C', 3, NULL, NULL, NULL, 4, '16:30:00', 7, '09:00:00', 'Yellow', 'Cäcilia Knoll', '+49(0)1476 264674', NULL, 'magnus22@hotmail.de', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 55),
(56, 9, NULL, 56, NULL, NULL, NULL, NULL, NULL, 1, '21:30:00', 2, '19:00:00', 'Magenta', 'Hans-Joachim Schütte-Straub', '+49(0) 320868908', NULL, 'ahmed33@horn.de', 1, '2022-09-02 14:19:40', '2022-09-02 14:19:40', 56),
(57, 8, 15, 57, 'A', 1, NULL, NULL, NULL, 4, '04:30:00', 3, '17:00:00', 'OrangeRed', 'Frau Emine Mayr B.Sc.', '(06497) 97320', NULL, 'rheinz@stark.com', 1, '2022-09-02 14:19:41', '2022-09-02 14:19:41', 57),
(58, 4, 15, 58, 'B', 2, NULL, NULL, NULL, 1, '01:30:00', 3, '03:00:00', 'DarkTurquoise', 'Frau Dr. Olga Kunz', '+49(0)4586 770918', NULL, 'andrea.wiesner@aol.de', 1, '2022-09-02 14:19:41', '2022-09-02 14:19:41', 58),
(59, 9, 15, 59, 'C', 3, NULL, NULL, NULL, 4, '06:30:00', 3, '13:00:00', 'Ivory', 'Herr Prof. Volker Dittrich B.A.', '(04387) 91713', NULL, 'ingelore.hess@moser.de', 1, '2022-09-02 14:19:41', '2022-09-02 14:19:41', 59),
(60, 6, NULL, 60, NULL, NULL, NULL, NULL, NULL, 1, '15:30:00', 3, '02:00:00', 'SeaGreen', 'Herr Prof. Alfred Beer MBA.', '(07840) 179935', NULL, 'aloisia.zander@googlemail.com', 1, '2022-09-02 14:19:41', '2022-09-02 14:19:41', 60),
(61, 2, 16, 61, NULL, NULL, NULL, NULL, NULL, 3, '19:30:00', 7, '17:00:00', 'Indigo', 'Katharina Eckert-Wunderlich', '+49(0)1875 60402', NULL, 'regine12@neuhaus.com', 1, '2022-09-02 14:19:41', '2022-09-02 14:19:42', 61),
(62, 9, 16, 62, NULL, NULL, NULL, NULL, NULL, 2, '21:30:00', 2, '18:00:00', 'FloralWhite', 'Bettina Weiss', '+49(0)6594 35092', NULL, 'georgios.kremer@freenet.de', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 62),
(63, 4, 16, 63, NULL, NULL, NULL, NULL, NULL, 1, '09:30:00', 5, '18:00:00', 'LightSeaGreen', 'Burkhard Heil-Neumann', '+49(0) 099285145', NULL, 'dittrich.edwin@aol.de', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 63),
(64, 5, NULL, 64, NULL, NULL, NULL, NULL, NULL, 5, '07:30:00', 6, '00:00:00', 'LimeGreen', 'Sigrun Weigel-Hentschel', '+49 (0) 5773 002870', NULL, 'mscharf@herold.de', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 64),
(65, 5, NULL, 65, NULL, NULL, NULL, NULL, NULL, 4, '18:30:00', 1, '10:00:00', 'DodgerBlue', 'Hans-Günter Hirsch', '0512874948', NULL, 'holger43@reichel.org', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 65),
(66, 7, NULL, 66, NULL, NULL, NULL, NULL, NULL, 4, '18:30:00', 2, '22:00:00', 'LightGoldenRodYellow', 'Gerta Marquardt', '07644349882', NULL, 'wenzel.isabella@kremer.net', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 66),
(67, 6, NULL, 67, NULL, NULL, NULL, NULL, NULL, 4, '13:30:00', 6, '03:00:00', 'PowderBlue', 'Margot Singer', '09791 14757', NULL, 'rmoll@seidl.de', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 67),
(68, 5, NULL, 68, NULL, NULL, NULL, NULL, NULL, 5, '12:30:00', 3, '06:00:00', 'Tan', 'Herr Prof. Dr. Eckhard Pietsch', '+49(0)0733 47559', NULL, 'laura.graf@kuhne.com', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 68),
(69, 3, 18, 69, 'A', 1, NULL, NULL, NULL, 4, '16:30:00', 4, '05:00:00', 'Green', 'Brigitte Steiner B.Eng.', '07188 73820', NULL, 'mayer.julia@jost.org', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:42', 69),
(70, 3, 18, 70, 'B', 2, NULL, NULL, NULL, 1, '07:30:00', 5, '08:00:00', 'Black', 'Herr Prof. Dr. Peer Funke B.Eng.', '(08075) 606760', NULL, 'eschade@posteo.de', 1, '2022-09-02 14:19:42', '2022-09-02 14:19:43', 70),
(71, 2, 18, 71, 'C', 3, NULL, NULL, NULL, 4, '02:30:00', 6, '10:00:00', 'Thistle', 'Eveline Kessler B.Eng.', '01584133386', NULL, 'kramer.dietrich@yahoo.de', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 71),
(72, 9, 18, 72, 'D', 4, NULL, NULL, NULL, 3, '18:30:00', 3, '18:00:00', 'Cornsilk', 'Leonhard Zimmermann', '+49(0)0151 29844', NULL, 'benz.ivo@schroter.de', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 72),
(73, 9, 19, 73, 'A', 1, NULL, NULL, NULL, 3, '20:30:00', 5, '00:00:00', 'Ivory', 'Herr Prof. Dr. Albert Seitz', '+49(0)4121968155', NULL, 'arndt98@mail.de', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 73),
(74, 8, 19, 74, 'B', 2, NULL, NULL, NULL, 5, '06:30:00', 7, '20:00:00', 'Thistle', 'Trude Binder', '+49(0) 261637555', NULL, 'henriette.brunner@jung.com', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 74),
(75, 7, 19, 75, 'C', 3, NULL, NULL, NULL, 4, '11:30:00', 4, '16:00:00', 'PaleTurquoise', 'Mina Wimmer B.Eng.', '+49(0) 662732996', NULL, 'obrandl@grimm.de', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 75),
(76, 2, 19, 76, 'D', 4, NULL, NULL, NULL, 2, '14:30:00', 5, '11:00:00', 'FireBrick', 'Teresa Jost', '07577 208887', NULL, 'johanna.barth@weber.com', 1, '2022-09-02 14:19:43', '2022-09-02 14:19:43', 76),
(77, 6, 20, 77, NULL, NULL, NULL, NULL, NULL, 3, '07:30:00', 6, '03:00:00', 'Black', 'Frau Valerie Meyer B.A.', '07401025092', NULL, 'bruckner.wolfdieter@hansen.com', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 77),
(78, 2, 20, 78, NULL, NULL, NULL, NULL, NULL, 4, '23:30:00', 4, '22:00:00', 'Sienna', 'Silvio Jäger-Weiss', '(07689) 079346', NULL, 'adam.hilda@mail.de', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 78),
(79, 8, 20, 79, NULL, NULL, NULL, NULL, NULL, 5, '12:30:00', 3, '02:00:00', 'Black', 'Hubert Kolb-Reich', '09744 014907', NULL, 'gotthard.keil@thiel.com', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 79),
(80, 3, 20, 80, NULL, NULL, NULL, NULL, NULL, 3, '06:30:00', 3, '20:00:00', 'Moccasin', 'Nikolaos Otto', '+49 (0) 2110 062800', NULL, 'inga11@live.de', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 80),
(81, 2, NULL, 81, NULL, NULL, NULL, NULL, NULL, 1, '22:30:00', 7, '14:00:00', 'Pink', 'Janusz Hansen', '03756902190', NULL, 'dhaupt@hofmann.net', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 81),
(82, 1, NULL, 82, NULL, NULL, NULL, NULL, NULL, 4, '06:30:00', 5, '16:00:00', 'DarkSalmon', 'Antonio Voß', '+49(0) 065373659', NULL, 'annelies90@eder.com', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 82),
(83, 4, NULL, 83, NULL, NULL, NULL, NULL, NULL, 4, '03:30:00', 5, '19:00:00', 'Green', 'Jeanette Rieger', '0683171037', NULL, 'wieland.sibylle@live.de', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:44', 83),
(84, 1, NULL, 84, NULL, NULL, NULL, NULL, NULL, 3, '00:30:00', 4, '17:00:00', 'Sienna', 'Herr Eckhardt Forster', '(02286) 22689', NULL, 'torsten.kirchner@freenet.de', 1, '2022-09-02 14:19:44', '2022-09-02 14:19:45', 84),
(85, 8, 22, 85, 'A', 1, NULL, NULL, NULL, 2, '21:30:00', 1, '11:00:00', 'Plum', 'Frau Prof. Magdalene Berndt', '+49(0)3552 541413', NULL, 'schubert.vitali@behrens.org', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 85),
(86, 5, 22, 86, 'B', 2, NULL, NULL, NULL, 4, '04:30:00', 4, '05:00:00', 'Black', 'Volker Kellner', '(01461) 684058', NULL, 'eugenie.wolter@gebhardt.com', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 86),
(87, 9, 22, 87, 'C', 3, NULL, NULL, NULL, 2, '16:30:00', 6, '07:00:00', 'White', 'Laura Steiner', '08991 10309', NULL, 'grossmann.hansgeorg@reich.com', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 87),
(88, 7, 22, 88, 'D', 4, NULL, NULL, NULL, 4, '12:30:00', 1, '14:00:00', 'Magenta', 'Volker Betz', '+49(0)5743 909604', NULL, 'rkarl@mail.de', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 88),
(89, 8, 23, 89, 'A', 1, NULL, NULL, NULL, 2, '14:30:00', 5, '04:00:00', 'LightCyan', 'Rosemarie Roth', '+49(0)3797 559083', NULL, 'dana.schlegel@yahoo.de', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 89),
(90, 3, 23, 90, 'B', 2, NULL, NULL, NULL, 5, '06:30:00', 5, '19:00:00', 'HoneyDew', 'Gregor Schumann-Ziegler', '(09692) 951303', NULL, 'nwalter@kellner.net', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 90),
(91, 1, 23, 91, 'C', 3, NULL, NULL, NULL, 4, '21:30:00', 3, '03:00:00', 'DimGrey', 'Herr Tino Erdmann', '06905 48755', NULL, 'rosmarie12@scheffler.de', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 91),
(92, 1, 23, 92, 'D', 4, NULL, NULL, NULL, 4, '21:30:00', 1, '10:00:00', 'Aquamarine', 'Pauline Schweizer', '09416465720', NULL, 'maren.stahl@gmail.com', 1, '2022-09-02 14:19:45', '2022-09-02 14:19:45', 92),
(93, 2, 24, 93, NULL, NULL, NULL, NULL, NULL, 5, '05:30:00', 2, '08:00:00', 'DarkOliveGreen', 'Dirk Heinz', '+49(0)3970491911', NULL, 'otmar.pape@hotmail.de', 1, '2022-09-02 14:19:46', '2022-09-02 14:19:46', 93),
(94, 2, 24, 94, NULL, NULL, NULL, NULL, NULL, 1, '18:30:00', 2, '21:00:00', 'Tomato', 'Herr Prof. Mustafa Gärtner', '05502 19450', NULL, 'awimmer@lutz.com', 1, '2022-09-02 14:19:46', '2022-09-02 14:19:46', 94),
(95, 7, 24, 95, NULL, NULL, NULL, NULL, NULL, 5, '08:30:00', 5, '09:00:00', 'MintCream', 'Isabella Heck B.Sc.', '+49(0)2534 683238', NULL, 'marta13@bender.com', 1, '2022-09-02 14:19:46', '2022-09-02 14:19:46', 95),
(96, 1, 24, 96, NULL, NULL, NULL, NULL, NULL, 1, '15:30:00', 7, '06:00:00', 'Teal', 'Lucie Brandl', '+49(0)5118 509964', NULL, 'gerold.eckert@bottcher.de', 1, '2022-09-02 14:19:46', '2022-09-02 14:19:46', 96),
(97, 5, NULL, 97, NULL, NULL, NULL, NULL, NULL, 2, '16:30:00', 3, '11:00:00', 'DimGray', 'Veit Bertram MBA.', '00541 334496', NULL, 'falko.strobel@nowak.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 97),
(98, 5, NULL, 98, NULL, NULL, NULL, NULL, NULL, 4, '23:30:00', 3, '17:00:00', 'Salmon', 'Meike Greiner', '(09661) 251805', NULL, 'anolte@bartels.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 98),
(99, 4, NULL, 99, NULL, NULL, NULL, NULL, NULL, 1, '02:30:00', 2, '04:00:00', 'MediumOrchid', 'Björn Friedrich', '(07824) 60037', NULL, 'romer.marian@heller.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 99),
(100, 5, NULL, 100, NULL, NULL, NULL, NULL, NULL, 4, '08:30:00', 3, '12:00:00', 'Orange', 'Gertrud Hohmann', '(06046) 124748', NULL, 'henke.adrian@strauss.com', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 100),
(101, 5, 26, 101, 'A', 1, NULL, NULL, NULL, 5, '19:30:00', 5, '14:00:00', 'SpringGreen', 'Hagen Lorenz', '08530 05593', NULL, 'dkeil@hotmail.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 101),
(102, 6, 26, 102, 'B', 2, NULL, NULL, NULL, 3, '18:30:00', 4, '22:00:00', 'DarkMagenta', 'Marta Franz', '+49 (0) 2669 194654', NULL, 'philipp.edelgard@hotmail.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 102),
(103, 8, 26, 103, 'C', 3, NULL, NULL, NULL, 1, '23:30:00', 5, '20:00:00', 'DarkOrchid', 'Philipp Moritz', '+49(0)0866 312240', NULL, 'bsteinbach@mail.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 103),
(104, 4, 26, 104, 'D', 4, NULL, NULL, NULL, 3, '06:30:00', 4, '04:00:00', 'SkyBlue', 'Frau Rose Wittmann', '(08517) 462197', NULL, 'ipfeifer@gmail.com', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:47', 104),
(105, 1, 27, 105, 'A', 1, NULL, NULL, NULL, 4, '11:30:00', 3, '05:00:00', 'MediumPurple', 'Piotr Wild MBA.', '+49 (0) 1230 684508', NULL, 'thilo.arnold@klose.de', 1, '2022-09-02 14:19:47', '2022-09-02 14:19:48', 105),
(106, 7, 27, 106, 'B', 2, NULL, NULL, NULL, 2, '01:30:00', 3, '14:00:00', 'BurlyWood', 'Frau Prof. Dr. Dorit Unger', '+49(0)5111 749438', NULL, 'funke.helga@mail.de', 1, '2022-09-02 14:19:48', '2022-09-02 14:19:48', 106),
(107, 2, 27, 107, 'C', 3, NULL, NULL, NULL, 3, '16:30:00', 7, '13:00:00', 'MistyRose', 'Herr Prof. Niels Kirchner', '(00577) 179945', NULL, 'miller.gertraude@live.de', 1, '2022-09-02 14:19:48', '2022-09-02 14:19:48', 107),
(108, 8, 27, 108, 'D', 4, NULL, NULL, NULL, 3, '02:30:00', 3, '02:00:00', 'LightGreen', 'Frau Prof. Svenja Kaiser B.Eng.', '(07226) 928773', NULL, 'willibald.behrens@googlemail.com', 1, '2022-09-02 14:19:48', '2022-09-02 14:19:48', 108),
(109, 2, 28, 109, NULL, NULL, NULL, NULL, NULL, 2, '15:30:00', 2, '19:00:00', 'MediumSlateBlue', 'Susann Heck', '+49(0) 431636329', NULL, 'reinhardt.heiner@t-online.de', 1, '2022-09-02 14:19:48', '2022-09-02 14:19:48', 109),
(110, 4, 28, 110, NULL, NULL, NULL, NULL, NULL, 2, '21:30:00', 6, '14:00:00', 'MediumSpringGreen', 'Magda Friedrich', '+49(0)3320586113', NULL, 'loswald@aol.de', 1, '2022-09-02 14:19:48', '2022-09-02 14:19:49', 110),
(111, 6, 28, 111, NULL, NULL, NULL, NULL, NULL, 1, '13:30:00', 1, '08:00:00', 'Green', 'Gunter Berg', '(05212) 496999', NULL, 'corina.zimmermann@thiel.com', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 111),
(112, 4, 28, 112, NULL, NULL, NULL, NULL, NULL, 1, '08:30:00', 4, '05:00:00', 'Chocolate', 'Reinhilde Rausch-Wolter', '+49(0) 160832505', NULL, 'otto.linke@bender.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 112),
(113, 5, NULL, 113, NULL, NULL, NULL, NULL, NULL, 2, '20:30:00', 3, '10:00:00', 'SteelBlue', 'Hardy Nowak MBA.', '00554280104', NULL, 'gottlieb.wendt@hotmail.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 113),
(114, 8, NULL, 114, NULL, NULL, NULL, NULL, NULL, 3, '02:30:00', 7, '13:00:00', 'MediumSlateBlue', 'Frau Jutta Ahrens MBA.', '05430 20336', NULL, 'dimitri.kirsch@metzger.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 114),
(115, 9, NULL, 115, NULL, NULL, NULL, NULL, NULL, 5, '10:30:00', 6, '18:00:00', 'DarkGray', 'Niels Herbst', '(08278) 917359', NULL, 'pzander@bachmann.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 115),
(116, 3, NULL, 116, NULL, NULL, NULL, NULL, NULL, 4, '12:30:00', 3, '05:00:00', 'Lavender', 'Vinzenz Auer', '02747 938521', NULL, 'behrens.janusz@wolter.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:49', 116),
(117, 5, 30, 117, 'A', 1, NULL, NULL, NULL, 5, '10:30:00', 4, '00:00:00', 'Fuchsia', 'Siegfried Rapp', '(09294) 259928', NULL, 'ignaz18@freenet.de', 1, '2022-09-02 14:19:49', '2022-09-02 14:19:50', 117),
(118, 9, 30, 118, 'B', 2, NULL, NULL, NULL, 5, '13:30:00', 6, '12:00:00', 'LightGray', 'Frau Prof. Dr. Pauline Wimmer', '+49(0) 059743216', NULL, 'romy69@nowak.de', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 118),
(119, 1, 30, 119, 'C', 3, NULL, NULL, NULL, 1, '17:30:00', 6, '08:00:00', 'SaddleBrown', 'Rene Seidl-Wimmer', '(08398) 02883', NULL, 'bianca.lang@jung.de', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 119),
(120, 9, 30, 120, 'D', 4, NULL, NULL, NULL, 5, '22:30:00', 3, '12:00:00', 'PeachPuff', 'Fatma Fink', '(04867) 53670', NULL, 'vera04@hotmail.de', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 120),
(121, 2, 31, 121, 'A', 1, NULL, NULL, NULL, 2, '11:30:00', 7, '17:00:00', 'DeepSkyBlue', 'Herr Prof. Dr. Torsten Hansen', '09182 274774', NULL, 'hartung.anne@web.de', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 121),
(122, 8, 31, 122, 'B', 2, NULL, NULL, NULL, 3, '19:30:00', 3, '16:00:00', 'Gray', 'Valerie Brand', '05079 734956', NULL, 'toni.hinz@t-online.de', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 122),
(123, 5, 31, 123, 'C', 3, NULL, NULL, NULL, 2, '13:30:00', 4, '03:00:00', 'MediumSeaGreen', 'Krystyna Michels', '0763587102', NULL, 'janine.reinhardt@karl.com', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 123),
(124, 9, 31, 124, 'D', 4, NULL, NULL, NULL, 2, '09:30:00', 4, '17:00:00', 'Turquoise', 'Xaver Kessler B.Eng.', '04215 66040', NULL, 'sigrun.kurz@wendt.net', 1, '2022-09-02 14:19:50', '2022-09-02 14:19:50', 124),
(125, 8, 32, 125, NULL, NULL, NULL, NULL, NULL, 4, '16:30:00', 5, '19:00:00', 'DarkMagenta', 'Hans-Günter Heim', '+49(0) 163381880', NULL, 'albers.maritta@henke.com', 1, '2022-09-02 14:19:51', '2022-09-02 14:19:51', 125),
(126, 2, 32, 126, NULL, NULL, NULL, NULL, NULL, 5, '02:30:00', 1, '22:00:00', 'RoyalBlue', 'Herr Prof. Adrian Maier', '+49(0)9360871123', NULL, 'friedrichwilhelm.merz@heinz.de', 1, '2022-09-02 14:19:51', '2022-09-02 14:19:51', 126),
(127, 2, 32, 127, NULL, NULL, NULL, NULL, NULL, 2, '23:30:00', 4, '17:00:00', 'Lime', 'Tobias Schuler-Röder', '+49 (0) 2021 038066', NULL, 'hubertus13@ruf.org', 1, '2022-09-02 14:19:51', '2022-09-02 14:19:51', 127),
(128, 6, 32, 128, NULL, NULL, NULL, NULL, NULL, 4, '20:30:00', 2, '18:00:00', 'Blue', 'Romy Brandl', '0985723183', NULL, 'wschreiber@block.de', 1, '2022-09-02 14:19:51', '2022-09-02 14:19:51', 128),
(129, 2, NULL, 129, NULL, NULL, NULL, NULL, NULL, 3, '11:30:00', 1, '23:00:00', 'LightPink', 'Herr Prof. Siegmar Schulze', '03798 99056', NULL, 'zweigel@haag.net', 1, '2022-09-02 14:19:51', '2022-09-02 14:19:51', 129),
(130, 1, NULL, 130, NULL, NULL, NULL, NULL, NULL, 5, '10:30:00', 2, '10:00:00', 'CornflowerBlue', 'Nikolai Heine', '05024775998', NULL, 'idittrich@hotmail.de', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 130),
(131, 8, NULL, 131, NULL, NULL, NULL, NULL, NULL, 3, '21:30:00', 1, '23:00:00', 'DarkBlue', 'Franz Wolter', '05818 291041', NULL, 'marcel79@bock.com', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 131),
(132, 4, NULL, 132, NULL, NULL, NULL, NULL, NULL, 1, '16:30:00', 1, '07:00:00', 'FloralWhite', 'Carina Klaus', '(08954) 43239', NULL, 'herdmann@wilhelm.org', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 132),
(133, 6, 34, 133, 'A', 1, NULL, NULL, NULL, 1, '19:30:00', 4, '16:00:00', 'Gray', 'Silke Brandt', '07761 560376', NULL, 'qschluter@hotmail.de', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 133),
(134, 4, 34, 134, 'B', 2, NULL, NULL, NULL, 4, '05:30:00', 5, '13:00:00', 'MediumOrchid', 'Lars Sauer', '+49(0) 365479004', NULL, 'hanno.koch@t-online.de', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 134),
(135, 1, 34, 135, 'C', 3, NULL, NULL, NULL, 2, '10:30:00', 7, '21:00:00', 'Chocolate', 'Ahmed Jost', '+49(0)9829 719689', NULL, 'zdittrich@live.de', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 135),
(136, 6, 34, 136, 'D', 4, NULL, NULL, NULL, 3, '00:30:00', 7, '20:00:00', 'Ivory', 'Sylke Hamann', '0315676067', NULL, 'kaiser.sabrina@web.de', 1, '2022-09-02 14:19:52', '2022-09-02 14:19:52', 136),
(137, 2, 35, 137, 'A', 1, NULL, NULL, NULL, 2, '01:30:00', 5, '16:00:00', 'DarkSlateGray', 'Frau Prof. Traute Kessler B.Sc.', '+49(0)4470 291213', NULL, 'niemann.fred@bock.org', 1, '2022-09-02 14:19:53', '2022-09-02 14:19:53', 137),
(138, 4, 35, 138, 'B', 2, NULL, NULL, NULL, 5, '11:30:00', 2, '11:00:00', 'MintCream', 'Frau Dr. Gunda Stein MBA.', '+49(0)3983703405', NULL, 'ludmilla.riedl@mail.de', 1, '2022-09-02 14:19:53', '2022-09-02 14:19:53', 138),
(139, 2, 35, 139, 'C', 3, NULL, NULL, NULL, 3, '00:30:00', 3, '14:00:00', 'FloralWhite', 'Nadine Wiedemann', '+49 (0) 5771 640771', NULL, 'margarethe.hennig@yahoo.de', 1, '2022-09-02 14:19:53', '2022-09-02 14:19:53', 139),
(140, 6, 35, 140, 'D', 4, NULL, NULL, NULL, 5, '21:30:00', 6, '06:00:00', 'Lime', 'Miroslav Miller MBA.', '+49(0) 348350406', NULL, 'uwitte@wieland.com', 1, '2022-09-02 14:19:53', '2022-09-02 14:19:53', 140),
(141, 1, 36, 141, NULL, NULL, NULL, NULL, NULL, 5, '11:30:00', 6, '17:00:00', 'LightGreen', 'Auguste Pfeifer MBA.', '+49(0) 130741616', NULL, 'else.rupp@sonntag.com', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 141),
(142, 2, 36, 142, NULL, NULL, NULL, NULL, NULL, 1, '21:30:00', 3, '11:00:00', 'Aqua', 'Karl-Josef Förster', '+49(0)5345017031', NULL, 'ivo.martens@freenet.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 142),
(143, 4, 36, 143, NULL, NULL, NULL, NULL, NULL, 4, '13:30:00', 6, '07:00:00', 'Yellow', 'Helmut Braun', '(06358) 25064', NULL, 'emichels@nolte.com', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 143),
(144, 7, 36, 144, NULL, NULL, NULL, NULL, NULL, 2, '10:30:00', 2, '21:00:00', 'DarkMagenta', 'Frau Dr. Irmtraud Voigt MBA.', '07426 95322', NULL, 'ieichhorn@martin.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 144),
(145, 7, NULL, 145, NULL, NULL, NULL, NULL, NULL, 5, '16:30:00', 5, '10:00:00', 'RosyBrown', 'Margit Mack', '+49 (0) 1164 151867', NULL, 'ybauer@web.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 145),
(146, 4, NULL, 146, NULL, NULL, NULL, NULL, NULL, 5, '20:30:00', 3, '03:00:00', 'PaleTurquoise', 'Gisela Hummel', '06414735847', NULL, 'geissler.toni@haas.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 146),
(147, 2, NULL, 147, NULL, NULL, NULL, NULL, NULL, 3, '03:30:00', 6, '20:00:00', 'Aqua', 'Viktoria Bertram', '+49 (0) 9664 007248', NULL, 'nico40@yahoo.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:54', 147),
(148, 8, NULL, 148, NULL, NULL, NULL, NULL, NULL, 2, '22:30:00', 7, '06:00:00', 'Yellow', 'Roger John', '08925250673', NULL, 'daniel45@yahoo.de', 1, '2022-09-02 14:19:54', '2022-09-02 14:19:55', 148),
(149, 3, 38, 149, 'A', 1, NULL, NULL, NULL, 1, '02:30:00', 2, '13:00:00', 'SlateBlue', 'Arnulf Rose-Langer', '(05844) 525648', NULL, 'vbock@jakob.de', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 149),
(150, 4, 38, 150, 'B', 2, NULL, NULL, NULL, 5, '14:30:00', 6, '04:00:00', 'LightGoldenRodYellow', 'Frau Katarina Bader', '+49 (0) 0498 735610', NULL, 'mayer.denise@zimmer.de', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 150),
(151, 6, 38, 151, 'C', 3, NULL, NULL, NULL, 1, '21:30:00', 6, '15:00:00', 'DarkCyan', 'Sören Kuhn', '+49(0)7851316473', NULL, 'forster.igor@neumann.org', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 151),
(152, 6, 38, 152, 'D', 4, NULL, NULL, NULL, 3, '13:30:00', 3, '14:00:00', 'DarkOliveGreen', 'Frau Prof. Dr. Constanze Metz B.Eng.', '(09853) 86859', NULL, 'rbetz@beyer.org', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 152),
(153, 4, 39, 153, 'A', 1, NULL, NULL, NULL, 2, '00:30:00', 6, '09:00:00', 'Gold', 'Lidia Kopp-Bachmann', '+49(0)3690383818', NULL, 'volker.richter@freenet.de', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 153),
(154, 2, 39, 154, 'B', 2, NULL, NULL, NULL, 5, '21:30:00', 3, '14:00:00', 'Blue', 'Frau Prof. Dr. Sara Eder B.Eng.', '05312 171412', NULL, 'cgiese@yahoo.de', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 154),
(155, 1, 39, 155, 'C', 3, NULL, NULL, NULL, 1, '07:30:00', 3, '10:00:00', 'MediumSeaGreen', 'Ana Haase', '0047622868', NULL, 'wahl.vincenzo@gmx.de', 1, '2022-09-02 14:19:55', '2022-09-02 14:19:55', 155),
(156, 9, 39, 156, 'D', 4, NULL, NULL, NULL, 1, '02:30:00', 2, '12:00:00', 'PaleGoldenRod', 'Frau Prof. Dr. Ivonne Stephan MBA.', '0502885381', NULL, 'ebar@mail.de', 1, '2022-09-02 14:19:56', '2022-09-02 14:19:56', 156),
(157, 3, 40, 157, NULL, NULL, NULL, NULL, NULL, 3, '05:30:00', 1, '12:00:00', 'DodgerBlue', 'Luise Schindler', '+49(0) 181255185', NULL, 'maier.irene@konrad.com', 1, '2022-09-02 14:19:56', '2022-09-02 14:19:56', 157),
(158, 9, 40, 158, NULL, NULL, NULL, NULL, NULL, 4, '10:30:00', 2, '17:00:00', 'LightGoldenRodYellow', 'Herr Dr. Erwin Funke', '05450 37421', NULL, 'geisler.hansjosef@yahoo.de', 1, '2022-09-02 14:19:56', '2022-09-02 14:19:56', 158),
(159, 1, 40, 159, NULL, NULL, NULL, NULL, NULL, 4, '00:30:00', 4, '04:00:00', 'Thistle', 'Frau Prof. Minna Weis B.A.', '(06366) 733184', NULL, 'uneuhaus@gmx.de', 1, '2022-09-02 14:19:56', '2022-09-02 14:19:56', 159),
(160, 9, 40, 160, NULL, NULL, NULL, NULL, NULL, 3, '11:30:00', 3, '20:00:00', 'BurlyWood', 'Herr Xaver Pape B.A.', '02091892233', NULL, 'margarethe.weber@posteo.de', 1, '2022-09-02 14:19:56', '2022-09-02 14:19:56', 160);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
