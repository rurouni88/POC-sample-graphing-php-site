<?php
# Process class. Business level processes here
class Process {
	public static function insertRPM($year, $month, $department, $rpm_value) {
		$conditions = array(
			'year'      => $year,
			'month'     => $month,
			'department'=> $department,
		);
		$exists = DAO\RPMData::Find($conditions);

		if ($exists) {
			throw new Exception('Record already exists at Id '.$exists[0]->id);
		}
		else {
			$object = new DAO\RPMData;
			$object->setYear($year);
			$object->setMonth($month);
			$object->setDepartment($department);
			$object->setRPMValue($rpm_value);
			$object->Create();
		}

		$rpm_objects = DAO\RPMData::Find($conditions);

		return $rpm_objects[0];
	}

	public static function editRPM($id, $year, $month, $department, $rpm_value) {
		$conditions = array(
			'id'        => $id,
			'year'      => $year,
			'month'     => $month,
			'department'=> $department,
		);
		$exists = DAO\RPMData::Find($conditions);

		if ($exists) {
			throw new Exception('Record already exists at Id '.$exists[0]->id);
		}
		else {
			$object = new DAO\RPMData;
			$object->setYear($year);
			$object->setMonth($month);
			$object->setDepartment($department);
			$object->setRPMValue($rpm_value);
			$object->Create();
		}

		$rpm_objects = DAO\RPMData::Find($conditions);

		return $rpm_objects[0];
	}

	public static function checkRPM ($year, $month, $department) {
		$conditions = array(
			year       => $year,
			month      => $month,
			department => $department,
		);
		$exists = DAO\RPMData::Find($conditions);

		return $exists[0];
	}

	public static function emailFeedback($to, $from, $name, $subject, $details) {

		require_once 'PHPMailer/class.phpmailer.php';

		$mail = new PHPMailer();
		$mail->IsSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use), 1 = client messages, 2 = client and server messages
		$mail->SMTPDebug  = 2;
		$mail->Debugoutput = 'html';
		$mail->Host = "mail.health.wa.gov.au";
		$mail->Port = 25; // Default 25, Can be 465 or 587
		$mail->SMTPAuth   = false;

		$mail->SetFrom($from, $name);

		$mail->AddAddress($to, 'PC');
		$mail->Subject = $subject;

		$mail->AltBody = $details;

		if(!$mail->Send()) {
			throw new Exception('Mailer Error: '.$mail->ErrorInfo);
		}

		return true;
	}
}

?>
