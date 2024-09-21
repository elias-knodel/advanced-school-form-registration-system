### Test Cases

#### User

- [ ] **testCreateUser**: Ensure a user can be created with valid data.
- [ ] **testUserRoleAssignment**: Assign different roles (Leser, Sekretär, Rektor) to a user and check permissions.
- [ ] **testInvalidRoleAssignment**: Attempt to assign an invalid role and expect failure.
- [ ] **testPatchUpdatePassword**: Check if the password can be updated by the user.
- [ ] **testUserSubmitAnfrage**: Verify if a user can submit an *Anfrage* (request) to a school.
- [ ] **testUserRequestRejected**: Verify behavior when a user’s request is rejected.
- [ ] **testViewOwnRequests**: Ensure a user can view their submitted requests.
- [ ] **testCannotEditApprovedRequest**: Ensure a user cannot edit a request that has already been approved by a school.

#### (School)

- [ ] **testCreateSchool**: Verify that a school entity can be created with valid data.
- [ ] **testCreateSchoolWithInvalidData**: Attempt to create a school with missing or invalid fields and expect failure.
- [ ] **testSchoolAssignRoles**: Check if the school can assign roles (Leser, Sekretär, Rektor) to different users.
- [ ] **testSchoolApproveRequest**: Ensure the school can approve a request.
- [ ] **testSchoolRejectRequest**: Ensure the school can reject a request.
- [ ] **testSchoolViewPendingRequests**: Verify that the school can view pending requests from users.
- [ ] **testSchoolCannotViewRejectedRequests**: Ensure that rejected requests are not visible to the school once rejected.

#### Request

- [ ] **testCreateAnfrage**: Ensure that a user can create a new request (Anfrage) and submit it to a school.
- [ ] **testInvalidAnfrageData**: Attempt to submit a request with missing or invalid fields and expect failure.
- [ ] **testRequestStatusTransition**: Test the transition of a request from "submitted" to "approved" or "rejected".
- [ ] **testUnauthorizedAnfrageCreation**: Ensure that only authorized users (with proper roles) can create a request.
- [ ] **testMultipleRequestsFromSameUser**: Verify behavior when a user submits multiple requests to the same school.

#### Form

- [ ] **testCreateFormForSchool**: Ensure that a form can be created and assigned to a school.
- [ ] **testInputFieldValidation**: Verify that all input fields (Vorname, Nachname, Ort, Alter) are validated correctly during form submission.
- [ ] **testFormSubmissionForRegistration**: Ensure that a user can submit the registration form with required fields.
- [ ] **testFormSubmissionForAnmeldung**: Verify that a user can submit the *Anmeldung* form with required fields.
- [ ] **testFormStateChangeAfterApproval**: Verify that once a request is approved by a school, the next form becomes available to the user.

#### Field

- [ ] **testVornameValidation**: Ensure that the *Vorname* (first name) field accepts valid input and rejects invalid data.
- [ ] **testNachnameValidation**: Ensure that the *Nachname* (last name) field accepts valid input and rejects invalid data.
- [ ] **testOrtValidation**: Verify that the *Ort* (location) field accepts valid input and rejects invalid data.
- [ ] **testAlterValidation**: Verify that the *Alter* (age) field accepts valid input and rejects invalid data.

#### Roles

- [ ] **testAssignLeserRole**: Ensure that a user can be assigned the "Leser" role and validate the permissions.
- [ ] **testAssignSekretaerRole**: Ensure that a user can be assigned the "Sekretär" role and validate the permissions.
- [ ] **testAssignRektorRole**: Ensure that a user can be assigned the "Rektor" role and validate the permissions.
- [ ] **testInvalidRoleAssignmentAttempt**: Attempt to assign a role not defined in the system and expect failure.
- [ ] **testRolePermissions**: Verify the specific permissions of each role for actions such as viewing or approving requests.
