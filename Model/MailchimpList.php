<?php

App::uses('MailchimpAppModel', 'Mailchimp.Model');

class MailchimpList extends MailchimpAppModel {

	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid e-mail address')));

	/**
	 * Use $_schema to set any mailchimp fields that you want to use
	 *
	 * @var array
	 */
	protected $_schema = array(
		'id' => array(
			'type' => 'int',
			'null' => true,
			'key' => 'primary',
			'length' => 11,
			),
		'email' => array(
			'type' => 'string',
			'null' => false,
			'length' => 256),
		'fname' => array(
			'type' => 'string',
			'null' => true,
			'key' => 'primary',
			'length' => 128),
		'lname' => array(
			'type' => 'string',
			'null' => true,
			'length' => 128),
		);

	/**
	 *
	 * Save a segment against a list for later use. There is no limit to the number of segments which can be saved.
	 * Static Segments are not tied to any merge data, interest groups, etc. They essentially allow you to configure
	 * an unlimited number of custom segments which will have standard performance. When using proper segments,
	 * Static Segments are one of the available options for segmentation just as if you used a merge var (and they
	 * can be used with other segmentation options), though performance may degrade at that point.
	 *
	 * Parameters
	 *
	 * @param name  string   a unique name per list for the segment - 100 byte maximum length, anything longer will throw an error
	 *
	 * @return mixed
	 */
	public function staticSegmentAdd($name) {

		$options = array(
			'id' => $this->settings['defaultListId'],
			'name' => $name
		);

		return $this->call('lists/static-segment-add', $options);
	}

	/**
	 * Add list members to a static segment. It is suggested that you limit batch size to no more than 10,000 addresses
	 * per call. Email addresses must exist on the list in order to be included - this will not subscribe them to the list!
	 *
	 * Parameters
	 *
	 * @param segmentId     int     the id of the static segment to modify - get from lists/static-segments()
	 * @param batch         array   an array of email structs, each with with one of the following keys:
	 *                      email   string    an email address
	 *                      euid    string    the unique id for an email address (not list related) - the email "id" returned from lists/member-info(), Webhooks, Campaigns, etc.
	 *                      leid    string    the list email id (previously called web_id) for a list-member-info type call. this doesn't change when the email address changes
	 *
	 * @param $batch
	 *
	 * @return mixed
	 */
	public function staticSegmentMemberAdd($segmentId, $batch){

		$options = array(
			'id' => $this->settings['defaultListId'],
			'seq_id' => $segmentId,
			'batch' => $batch
		);

		return $this->call('lists/static-segment-member-add', $options);
	}

}
