/*
Unresolved questions -
1. some duplicate rows in tepsurveys - same latitude(0.000), longitue and date - validity in # surveys?
2. *surveys tables search only for first survey or even for recent survey? Reason - recent survey results
varying for memberintsurvey and *survey tables
3. what about last 12 months
*/

SELECT memberid,datereceived,amount
FROM contrib
WHERE memberid=12
ORDER BY datereceived DESC
LIMIT 10;

/*get the last/recent contribution year and amount of a member*/
SELECT memberid, YEAR(datereceived) AS yr, SUM(amount) AS total
FROM contrib
WHERE memberid=10067
GROUP BY yr
ORDER BY datereceived DESC
LIMIT 1;

/*get the contribution amount in the last 12 months*/
SELECT SUM(amount)
FROM contrib
WHERE memberid=33133 AND datereceived < NOW() AND datereceived > DATE_ADD(NOW(), INTERVAL- 12 MONTH)
GROUP BY memberid;

/*get the total amount contributed by the person*/
SELECT memberid, SUM(amount) AS amt
FROM contrib
WHERE memberid=10067
GROUP BY memberid;

/*first contributed date*/
SELECT memberid,datereceived,amount
FROM contrib
WHERE memberid=10067
ORDER BY datereceived
LIMIT 1;

--SURVEYS related queries
--date first survey
SELECT surveydate
FROM memberintsurveys
WHERE memberid=11
ORDER BY surveydate ASC
LIMIT 1;

--date last survey
SELECT surveydate
FROM memberintsurveys
WHERE memberid=11
ORDER BY surveydate DESC
LIMIT 1;

--Total # survyes
SELECT memberid, COUNT(*) AS total_surveys
FROM memberintsurveys
WHERE memberid=11
GROUP BY memberid;

--Surveys in last 12 months
SELECT memberid, COUNT(*) AS surveys_last_12_months
FROM memberintsurveys
WHERE memberid=11 AND surveydate < NOW() AND surveydate > DATE_ADD(NOW(), INTERVAL- 12 MONTH)
GROUP BY memberid;

--experience levels
SELECT region, EXP AS LEVEL, DATE
FROM member_exp
WHERE memberid=11
ORDER BY DATE DESC;


------------
/* Pulling survey results (min date, max date etc) from all different survey tables*/

/* First survey date */
CREATE OR
REPLACE VIEW minview AS
select min(date) date from cipsurveys where memberid=11 
union
select min(date) from hawsurveys where memberid=11
union
select min(date) from nesurveys where memberid=11
union
select min(date) from pacsurveys where memberid=11
union
select min(date) from sassurveys where memberid=11
union
select min(date) from sopsurveys where memberid=11
union
select min(date) from tepsurveys where memberid=11
union
select min(date) from twasurveys where memberid=11;

select min(date) from minview;

/* Most recent survey date */
CREATE OR
REPLACE VIEW maxview AS
select max(date) date from cipsurveys where memberid=11 
union
select max(date) from hawsurveys where memberid=11
union
select max(date) from nesurveys where memberid=11
union
select max(date) from pacsurveys where memberid=11
union
select max(date) from sassurveys where memberid=11
union
select max(date) from sopsurveys where memberid=11
union
select max(date) from tepsurveys where memberid=11
union
select max(date) from twasurveys where memberid=11;

select max(date) from minview;

DROP VIEW IF EXISTS maxview;



--YTD - Year to Date - Total donation in the current year
SELECT SUM(amount)
FROM contrib
WHERE YEAR(datereceived)= YEAR(NOW());

--Months from last donation
SELECT FLOOR(DATEDIFF(CURDATE(), MAX(datereceived))/30)
FROM contrib
WHERE memberid=33133

--table with columns : year,amt,#don
SELECT YEAR(datereceived) year, SUM(amount) sum, COUNT(*) number
FROM contrib
WHERE memberid=33133
GROUP BY YEAR;

select * from tepsurveys where memberid=11;

--no. of surveys in last 12 months from *surveys tables result combined 
--NOTE that you have to use UNION ALL and include duplicates
CREATE OR
REPLACE VIEW countview AS
SELECT COUNT(*) c
FROM cipsurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM hawsurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM nesurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM pacsurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM sassurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM sopsurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM tepsurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH) UNION ALL
SELECT COUNT(*) c
FROM twasurveys
WHERE memberid=11 AND DATE < NOW() AND DATE > DATE_ADD(NOW(), INTERVAL- 12 MONTH);

select sum(c) from countview;

--Total number of surveys - result from *surveys tables
CREATE OR
REPLACE VIEW totalview AS
SELECT COUNT(*) c
FROM cipsurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM hawsurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM nesurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM pacsurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM sassurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM sopsurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM tepsurveys
WHERE memberid=11 UNION ALL
SELECT COUNT(*) c
FROM twasurveys
WHERE memberid=11;

select sum(c) from totalview;


/* IMP - contribmethod table DOEST NOT EXIST currently, so create it manually in the remote server - 
Creating a new table contribmethod for add contribution. For sample creation query look below */
CREATE TABLE IF NOT EXISTS contribmethod (id INT PRIMARY KEY, name VARCHAR(30));

INSERT INTO contribmethod (id,name) VALUES (0,'Unknown'),(1,'Cash'),(2,'Check'),(3,'Credit Card - Phone'),
(5,'Goods'),(6,'In Kind Services'),(7,'Stock'),(8,'Credit Card Website'),(9,'Corporate Matching');

SELECT * FROM contribmethod;

--Sample Creation MySQL queries
CREATE DATABASE IF NOT EXISTS `reef` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `reef`;

CREATE TABLE IF NOT EXISTS `member_exp` (
  `expid` int(5) NOT NULL AUTO_INCREMENT,
  `memberid` int(5) NOT NULL DEFAULT '0',
  `region` char(3) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `exp` tinyint(1) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`expid`),
  KEY `membedrid` (`memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--expid will be auto-generated
INSERT INTO `member_exp` (`memberid`, `region`, `exp`, `date`) VALUES
	(1, 'TWA', 5, '1994-02-15'),
	(12, 'TWA', 5, '1994-02-15');
--end