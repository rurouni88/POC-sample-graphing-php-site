<div id="about">
<h3>About</h3>
<p>
	<strong>Position: 602478</strong> - Senior Information Systems Development Officer<br />
	<strong>Workplace:</strong> Royal Perth Hospital<br />
	<strong>Subject:</strong> Interview requirements
</p>

<p>
Prior to attending the interview, we ask that you create a simple web site to the specifications given below
and bring it with you to the interview. The actual interview process should take about 1 hour and 15 minutes
consisting of:
<ul>
	<li>10 minutes to upload your sample web site to our web server.</li>
	<li>20 minutes to provide written answers to a set of questions provided on the day of the interview.</li>
	<li>45 minutes to be interviewed by the selection panel. As part of the interview process you will be
asked to demonstrate the operation of your sample website and discuss the design decisions and
techniques used in its design.</li>
</ul>
</p>

<p>
<p><strong>Web site design task</strong></p>
You are asked to create a web site that enables users to enter data about the numbers of Personal Radiation
Monitors (PRMs) that are returned to the Hospital by departments for processing.
Your web site should include at least the following pages:
<ul>
	<li>A Home page providing access to the other pages.</li>
	<li>A Data Entry page where users enter the numbers of returned PRMs for each month and department.</li>
</ul>

You should allow for up to 12 months data to be entered. Currently three departments (Cardiology,
Radiology and Medical Physics) are being monitored, but this is expected to increase in the future;
the design should allow for an increase in departments without the need for any recoding.

A Calculations page that calculates and displays various statistics that you think relevant to the data.
Users are unsure what statistics they require and leave it in the hands of the programmer to decide.
<ul>
	<li>A Graphs page that presents the data in graphical form. Users are unsure what type of graph they
require and leave it in the hands of the programmer to decide.</li>
	<li> Feedback page that emails comments to robert.pezzarini@health.wa.gov.au</li>
</ul>
You are encouraged to make design decisions by yourself without recourse to the end user, but if you do
have any technical questions, please email them to robert.pezzarini@health.wa.gov.au. If a response is
deemed appropriate, it will be emailed to all candidates with the original question. Feel free to embellish
your site as you see fit.
</p>

<p>
<strong>Design notes</strong>
<ul>
	<li>The site should be completely self-contained.</li>
	<li>You will be given ftp access to the web server to upload your site. The web site name is mc6420 and
you will be allocated a page named your surname. For example, Robert Pezzarini will be allocated
the page "http://mc6420/pezzarini/".</li>
	<li>The Hospital has a mail server named mail.health.wa.gov.au that allows anonymous smtp access.</li>
	<li>The web server is a Windows 2003 Server R2 running IIS V6.</li>
	<li>Available technologies on the web server are VBScript and PHP V5.3.8.</li>
	<li>Users have Windows XP PC's running Internet Explorer 8 with Microsoft Office 2003 Professional
installed.</li>
	<li>A sample of the type of data to be entered manually is shown in the table below.</li>
</ul>

<pre>
    Radiology   Cardiology  Medical Physics
Jan       100           20                8
Feb       150           25                8
Mar        95           22                7
Apr        60           21                7
May        70           18                8
Jun        50           10                8
</pre>
</p>
</div>
