<?php
namespace Craft;

class SproutReportsUsersDataSource extends SproutReportsBaseDataSource
{
	public function getName()
	{
		return Craft::t('Users');
	}

	public function getDescription()
	{
		return Craft::t('Create reports about your users and user groups.');
	}

	// @todo - conditionally adjust the labels depending on which columns are returned
	//public function getDefaultLabels()
	//{
	//	return array(
	//			'Username',
	//			'Email',
	//			'First Name',
	//			'Last Name'
	//	);
	//}

	/**
	 * @param  SproutReports_ReportModel &$report
	 *
	 * @return array|null
	 */
	public function getResults(SproutReports_ReportModel &$report)
	{
		$options = $report->getOptions();

		$userGroupIds            = $options['userGroups'];
		$displayUserGroupColumns = $options['displayUserGroupColumns'];

		$includeAdmins = false;

		if (is_array($userGroupIds) && in_array('admin', $userGroupIds))
		{
			$includeAdmins = true;

			// Admin is always the first in our array if it exists
			unset($userGroupIds[0]);
		}

		$userGroups = craft()->userGroups->getAllGroups();

		$userGroupsByName = array();
		foreach ($userGroups as $userGroup)
		{
			$userGroupsByName[$userGroup->name] = null;
		}

		$userQuery = craft()->db->createCommand()
			->select('{{users.id}},
								{{users.username}},
								{{users.email}},
								{{users.firstName}},
								{{users.lastName}}
								')
			->from('users')
			->join('usergroups_users', '{{users.id}} = {{usergroups_users.userId}}');

		if (is_array($userGroupIds))
		{
			$userQuery->where(array('in', '{{usergroups_users.groupId}}', $userGroupIds));
		}

		if ($includeAdmins)
		{
			$userQuery->orWhere('{{users.admin}} = 1');
		}

		$userQuery->group('{{users.id}}');

		// @todo - can we query users and user their ids as the array key?
		$users = $userQuery->queryAll();

		// Update users to be indexed by their ids
		$usersById = array();
		foreach ($users as $user)
		{
			$usersById[$user['id']] = $user;
			unset ($usersById[$user['id']]['id']);
		}

		$userGroupsMapQuery = craft()->db->createCommand()
			->select('*')
			->from('usergroups_users')
			->join('usergroups', '{{usergroups.id}} = {{usergroups_users.groupId}}')
			->queryAll();

		// Create a map of all users and which user groups they are in
		$userGroupsMap = array();
		foreach ($userGroupsMapQuery as $userGroupsUser)
		{
			$userGroupsMap[$userGroupsUser['userId']][$userGroupsUser['name']] = true;
		}

		// Add and identify User Groups as columns
		foreach ($usersById as $key => $user)
		{
			if ($displayUserGroupColumns)
			{
				// Add User Groups as columns to user array
				$user = array_merge($user, $userGroupsByName);

				if (isset($userGroupsMap[$key]))
				{
					// Match users to the user groups they are in
					$user = array_merge($user, $userGroupsMap[$key]);
				}
			}

			$usersById[$key] = $user;
		}

		return $usersById;
	}

	/**
	 * @param array $options
	 *
	 * @return string
	 */
	public function getOptionsHtml(array $options = array())
	{
		$userGroups = craft()->userGroups->getAllGroups();

		$userGroupOptions[] = array(
			'label' => 'Admin',
			'value' => 'admin'
		);

		foreach ($userGroups as $userGroup)
		{
			$userGroupOptions[] = array(
				'label' => $userGroup->name,
				'value' => $userGroup->id
			);
		}

		$optionErrors = array_shift($this->report->getErrors('options'));

		return craft()->templates->render('sproutreports/datasources/_options/users', array(
			'userGroupOptions' => $userGroupOptions,
			'options'          => $this->report->getOptions(),
			'errors'           => $optionErrors
		));
	}

	/**
	 * Validate our data source options
	 *
	 * @param array $options
	 * @return array|bool
	 */
	public function validate(array $options = array())
	{
		$errors = null;

		if (empty($options['userGroups']))
		{
			$errors['userGroups'][] = Craft::t('Select at least one User Group.');

			return $errors;
		}

		return true;
	}
}
