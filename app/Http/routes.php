<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**API*/
Route::post('api/signup','ApiController@signup');
Route::post('api/verify/account','ApiController@signupComplete');
Route::post('api/login','ApiController@login');
Route::post('api/forgot/password','ApiController@forgotPassword');
Route::post('api/verify/password','ApiController@verifyForgotPasswordOtp');
Route::post('api/save/new-password','ApiController@savePasswordOtp');
Route::post('api/update/password','ApiController@changePassword');
Route::post('api/update/profile','ApiController@updateProfile');
Route::post('api/update/company/details','ApiController@signupUpdateCompanyDetails');
Route::post('api/get/industries','ApiController@getIndustries');
Route::post('api/get/business/types','ApiController@businessTypes');
Route::post('api/signup/business/types','ApiController@updateBusinessTypes');
Route::post('api/signup/complete/onboarding','ApiController@completeOnboarding');
Route::post('api/signup/complete/walkthrough','ApiController@completeOnboardingWalkthrough');
Route::post('api/signup/developer/invite','ApiController@signupDeveloperInvite');
Route::post('api/twilio/phone/owner','ApiController@getTwilioPhoneOwnerName');
Route::post('api/update/first/call','ApiController@updateFirstCallStage');

/**Events*/
Route::post('api/add/event','ApiController@addEvent');
Route::post('api/calendar/slot/events','ApiController@getCalendarEventSlots');
Route::post('api/load/calendar/events','ApiController@getCalendarEvents');
Route::post('api/load/event/details','ApiController@getEventDetails');
Route::post('api/delete/event','ApiController@deleteEvent');

Route::post('api/lead/statuses','ApiController@getLeadStatuses');
Route::post('api/event/statuses','ApiController@getEventStatuses');
Route::post('api/update/event','ApiController@updateEvent');
Route::post('api/add/client','ApiController@addClient');
Route::post('api/list/clients','ApiController@getClientsList');
Route::post('api/client/details','ApiController@getClientDetails');
Route::post('api/update/client','ApiController@updateClient');
Route::post('api/update/client/status','ApiController@updateClientStatus');
Route::post('api/delete/client','ApiController@deleteClient');
Route::post('api/load/phone/countries','ApiController@getAvailablePhoneCountries');
Route::post('api/load/available/numbers','ApiController@getAvailablePhoneNumbers');
Route::post('api/purchase/phone','ApiController@purchasePhoneNumber');
Route::post('api/user/phones','ApiController@getUserPhones');
Route::post('api/delete/phone','ApiController@deletePhone');
Route::post('api/call/history','ApiController@callHistory');
Route::post('api/call/add-dial-history','ApiController@dialCallHistory');
Route::post('api/call/available-hours','ApiController@getAvailableHours');
Route::post('api/twilio/incoming/call','ApiController@twilioIncomingCallTrack');
Route::post('api/get/twilio/account','ApiController@getTwilioPhoneDetails');
Route::post('api/get/phone/client','ApiController@getClientByPhone');
Route::post('api/load/messages','ApiController@loadMessages');
Route::post('api/load/topic/messages','ApiController@loadTopicMessages');
Route::post('api/send/text/message','ApiController@sendTextMessage');
Route::post('api/delete/topic/messages','ApiController@deleteTopicMessages');
Route::post('api/mark/read/topic/messages','ApiController@markTopicMessagesRead');
Route::post('api/twilio/incoming/text','ApiController@twilioIncomingTextMessage');
Route::post('api/task/totals','ApiController@getTaskTotals');
Route::post('api/load/client/tasks','ApiController@loadClientTasks');
Route::post('api/load/global/tasks','ApiController@loadGlobalTasks');
Route::any('api/load/missed/items','ApiController@loadMissedItems');
Route::post('api/load/followup/items','ApiController@loadFollowUpItems');
Route::post('api/set/lead','ApiController@setLead');
Route::post('api/form/details','ApiController@getFormDetails');
Route::post('api/form/set/lead','ApiController@setFormLead');
Route::post('api/latest/interaction','ApiController@getLatestInteraction');
Route::post('api/phone/countries','ApiController@getPhoneCountries');

/**New APIs*/
Route::any('api/check/email','ApiController@checkEmailRegistered');
Route::post('api/update/password','ApiController@updatePassword');
Route::post('api/update/user','ApiController@updateUser');
Route::post('api/signup/email/otp','ApiController@sendSignupVerificationCode');
Route::post('api/signup/email/confirm','ApiController@confirmSignupVerificationCode');
Route::post('api/full/signup','ApiController@fullSignup');
Route::post('api/xero/disable','ApiController@disableXero');
Route::post('api/xero/status','ApiController@getXeroStatus');
Route::post('api/xero/connect','ApiController@xeroConnect');

/**Global Tasks*/
Route::post('api/global/task/create','ApiController@addGlobalTask');
Route::post('api/global/task/update','ApiController@updateGlobalTask');
Route::post('api/global/task/delete','ApiController@deleteGlobalTask');

/**Client Tasks*/
Route::post('api/client/task/create','ApiController@addClientTask');
Route::post('api/client/task/update','ApiController@updateClientTask');
Route::post('api/client/task/delete','ApiController@deleteClientTask');

/**Client Activity*/
Route::post('api/client/activity','ApiController@getClientActivity');

/**Invoices*/
Route::post('api/countries','ApiController@countries');
Route::post('api/add/invoice','ApiController@addInvoice');
Route::post('api/delete/invoice','ApiController@deleteInvoice');
Route::post('api/invoice/details','ApiController@invoiceDetails');
Route::post('api/invoice/send','ApiController@sendInvoice');
Route::post('api/invoices','ApiController@getInvoices');
Route::post('api/invoice/paid','ApiController@setInvoicePaid');

/**Device Tokens*/
Route::post('api/add/device/token','ApiController@addDeviceToken');
Route::post('api/unregister/device/token','ApiController@unregisterDeviceToken');

/**Other*/
Route::post('api/client/location/types','ApiController@getClientLocationTypes');
Route::post('api/tradiereview/iframe','ApiController@tradieReviewsMobileIframe');
Route::post('api/tradiereview/check','ApiController@tradieReviewsAccessCheck');
Route::post('api/tradiereview/send/review','ApiController@sendTradieReviewReview');

/**Test*/
Route::get('api/test','ApiController@test');
Route::post('api/test','ApiController@test');
Route::post('api/custom/logout','ApiController@removeTestAccount');

/**Auth for regular users*/
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/forgot-password', 'Auth\AuthController@getPasswordReset');
Route::post('auth/forgot-password', 'Auth\AuthController@postPasswordReset');
Route::get('auth/forgot-password/verify', 'Auth\AuthController@verifyPassword');
Route::post('auth/forgot-password/verify', 'Auth\AuthController@checkPasswordVerificationCode');
Route::get('auth/reset-password', 'Auth\AuthController@resetPassword');
Route::post('auth/saveNewPassword', 'Auth\AuthController@saveNewPassword');
Route::post('free-trial','Auth\AuthController@postStartFreeTrial');
Route::get('free-trial/verify','Auth\AuthController@verifyRegister');
Route::post('free-trial/verify/check','Auth\AuthController@verifyRegisterCheck');
Route::get('free-trial/set/password','Auth\AuthController@setRegisterPassword');
Route::post('free-trial/set/password','Auth\AuthController@saveRegisterNewPassword');
Route::get('referrals/{code}','Auth\AuthController@referral');

/**Register versions*/
Route::get('free-trial','Auth\AuthController@register');
Route::post('free-trial','Auth\AuthController@postRegister');
Route::get('free-trial-2/verify','Auth\AuthController@verifyRegisterVersion');
Route::post('free-trial-2/verify','Auth\AuthController@verifyRegisterProcess');
Route::get('free-trial-2/password','Auth\AuthController@setRegisterVersionPassword');
Route::post('free-trial-2/password','Auth\AuthController@registerVersionSetPassword');
Route::get('free-trial-2/step/1','Auth\AuthController@registerStep1');
Route::post('free-trial-2/step/1','Auth\AuthController@registerProcessStep1');
Route::get('free-trial-2/step/2','Auth\AuthController@registerStep2');
Route::post('free-trial-2/step/2','Auth\AuthController@registerProcessStep2');
Route::get('free-trial-2/step/3','Auth\AuthController@registerStep3');
Route::post('free-trial-2/step/3','Auth\AuthController@registerProcessStep3');
Route::get('free-trial-2','Auth\AuthController@registerPopup');
Route::get('complete/registration/{id}','Auth\AuthController@completeSpecialOfferRegistration');

/**Landing Pages*/
Route::get('/', 'LandingPageController@index');
Route::get('free-demo','LandingPageController@demo');
Route::get('early-access/{type?}','LandingPageController@earlyAccess');
Route::post('early-access/purchase','LandingPageController@purchaseEarlyAccess');
Route::get('dashboard', 'DashboardController@index');
Route::get('privacy-policy','LandingPageController@privacyPolicy');
Route::get('terms','LandingPageController@terms');
Route::get('cookies','LandingPageController@cookies');
Route::post('newsletter/subscribe','LandingPageController@subscribe');
Route::get('newsletter/subscribed','LandingPageController@setSubscriberDetails');
Route::get('industries','LandingPageController@industries');
Route::get('mobile/xero/connect/{id}','LandingPageController@connectMobileXero');
Route::get('mobile/xero/loading','LandingPageController@mobileXeroLoading');
Route::get('contact-us','LandingPageController@contactUs');
Route::post('contact-us','LandingPageController@handleContactUs');
Route::post('load/landing/pricing','LandingPageController@getGeoCountry');

/**Reviews*/
Route::get('rate/job/{id}','LandingPageController@rateJob');
Route::post('rate/job','LandingPageController@postReview');
Route::get('reviews','ReviewsController@index');
Route::post('reviews/filter','ReviewsController@filter');
Route::get('reviews/setup','ReviewsController@setup');
Route::patch('reviews/setup','ReviewsController@update');
Route::post('reviews/send/invite','ReviewsController@sendInvite');
Route::get('rate/{id}','LandingPageController@reviewInvite');
Route::post('reviews/business/logo','ReviewsController@uploadBusinessLogo');
Route::post('reviews/remove/logo','ReviewsController@removeBusinessLogo');
Route::get('review/{id}','LandingPageController@addPublicReview');

/**Referrals*/
Route::get('referrals','ReferralsController@index');
Route::post('referrals/send/invite','ReferralsController@referralInvite');

/**Invoice*/
Route::get('invoice/{id}','LandingPageController@invoice');
Route::get('invoice/{id}/pay','LandingPageController@payInvoice');
Route::post('invoice/pay','LandingPageController@payInvoiceProcess');

/**Resources*/
Route::resource('client', 'ClientController');
Route::resource('quote','QuoteController');
Route::resource('invoices','InvoicesController');

/**Clients*/
Route::post('client/update/status','ClientController@updateStatus');
Route::post('client/delete','ClientController@destroy');
Route::post('client/load/funnel','ClientController@loadFunnelData');
Route::get('client/{id}/set/{type}','ClientController@setStatus');
Route::post('client/add/event','ClientController@addEvent');
Route::post('client/add/follow-up','ClientController@addFollowUp');
Route::post('client/note/create','ClientController@addNote');
Route::post('client/task/create','ClientController@addTask');
Route::post('client/task/check','ClientController@checkTask');
Route::post('client/task/search','ClientController@searchTaskClient');
Route::post('client/lead/information','ClientController@leadInformation');
Route::post('client/load/history','ClientController@loadHistory');
Route::post('client/history/events','ClientController@loadEventsHistory');
Route::post('client/event/delete','ClientController@deleteEvent');
Route::post('client/form/details','ClientController@formDetails');
Route::get('client/{id}/pre/call','ClientController@preCall');
Route::post('client/invoice/details','ClientController@invoiceDetails');

/**Invoices*/
Route::get('invoices/{id}/payment-received','InvoicesController@paymentReceived');
Route::get('invoices/{id}/duplicate','InvoicesController@duplicate');
Route::post('invoices/delete','InvoicesController@delete');
Route::post('invoices/client/search','InvoicesController@clientSearch');

/**History*/
Route::get('history','HistoryController@index');
Route::post('history/details','HistoryController@details');
Route::get('history/download/recording/{id}','HistoryController@downloadRecording');
Route::post('history/client/search','HistoryController@searchClients');
Route::get('history/pre/call/{id}/{type}','HistoryController@preCall');
Route::post('history/track','HistoryController@dialCallHistoryTrack');

/**Settings Pages*/
Route::get('settings', 'SettingsController@index');
Route::get('settings/calendar', 'SettingsController@calendar');
Route::patch('settings/calendar', 'SettingsController@updateCalendar');
Route::get('settings/account', 'SettingsController@account');
Route::patch('settings/account', 'SettingsController@updateAccount');
Route::get('settings/invoices', 'SettingsController@invoice');
Route::patch('settings/invoices', 'SettingsController@updateInvoice');
Route::get('settings/security', 'SettingsController@security');
Route::patch('settings/security', 'SettingsController@updateSecurity');
Route::get('settings/subscriptions','SettingsController@subscriptions');
Route::post('settings/update/card','SettingsController@updateCard');
Route::post('settings/update/subscription', 'SettingsController@updateSubscription');
Route::post('settings/remove/user/card','SettingsController@removeUserCard');
Route::post('settings/cancel/renewal','SettingsController@cancelRenewal');
Route::post('settings/subscriptions/discount','SettingsController@checkDiscountCode');
Route::get('settings/xero/connect','SettingsController@connectXero');
Route::get('settings/xero/account','SettingsController@xeroResponse');
Route::get('settings/forms','SettingsController@forms');
Route::post('settings/form/track','SettingsController@trackForms');
Route::post('settings/forms/website','SettingsController@getWebsiteDetails');
Route::post('settings/forms/check/tracking','SettingsController@checkFormAllowTracking');
Route::post('settings/forms/remove/form','SettingsController@removeAllFormPages');
Route::post('settings/forms/remove/page','SettingsController@removeFormPage');
Route::post('settings/forms/page/track','SettingsController@updateFormPageTracking');
Route::post('settings/forms/manual/tracking','SettingsController@manualFormTracking');
Route::post('settings/forms/update/form/title','SettingsController@updateFormTitle');
Route::get('settings/phone-numbers','SettingsController@phoneNumbers');
Route::post('settings/phone-numbers','SettingsController@updatePhoneNumbers');
Route::get('settings/skip/{type}','SettingsController@skipOnboarding');
Route::get('settings/integrations','SettingsController@integrations');
Route::post('settings/xero/remove','SettingsController@xeroRemove');
Route::get('settings/email','SettingsController@email');
Route::post('settings/integrations','SettingsController@updateIntegrations');
Route::get('settings/remove/xero','SettingsController@removeXero');
Route::post('settings/xero/check','SettingsController@checkXero');
Route::post('settings/check/subscription','SettingsController@checkSubscription');
Route::post('settings/au/phone/address','SettingsController@purchaseNewAddress');
Route::get('setup/tradieflow','SettingsController@setupTradieFlow');
Route::get('onboarding','SettingsController@onboarding');
Route::get('onboarding-demo','SettingsController@onboardingDemo');
Route::get('developer/setup/{code}','LandingPageController@setupDeveloperAccount');
Route::post('developer/forms/tracking','LandingPageController@developerFormsTracking');
Route::get('get/tradiereviews','SettingsController@getTradieReviews');

/**Demo*/
Route::post('settings/update/lead/status','SettingsController@updateLeadStatus');

/**Settings Emails*/
//Route::get('settings/emails', 'SettingsController@emails');
//Route::patch('settings/emails', 'SettingsController@updateEmails');
//Route::get('settings/users', 'SettingsController@users');
//Route::patch('settings/users', 'SettingsController@updateUsers');

///**Settings Security*/

/**Settings Help*/
Route::get('ready-to-go', 'SettingsController@readyToGo');

/**Calendar*/
Route::get('calendar','CalendarController@index');
Route::get('calendar/events','CalendarController@loadEvents');
Route::get('calendar/client/search','CalendarController@searchClient');

/**Dashboard*/
Route::post('call/flow/token','DashboardController@getCallFlowToken');
Route::post('call/flow/incoming','DashboardController@incomingCall');
Route::post('dashboard/notifications', 'DashboardController@getNotifications');
Route::post('call/make','DashboardController@makeCall');
Route::post('call/flow/track','DashboardController@callStatusTrack');

/**Quotes*/
Route::post('quote/company/logo','QuoteController@uploadCompanyLogo');

/**Admin*/
Route::get('admin','AdminController@dashboard');
Route::get('admin/generate','AdminController@generate');
Route::post('admin/generate','AdminController@processLeadGeneration');
Route::get('admin/referrals','AdminController@referrals');
Route::post('admin/referrals','AdminController@sendReferral');
Route::get('accept/referral/{id}','LandingPageController@acceptAdminReferralOffer');
Route::get('reject/referral/{id}','LandingPageController@rejectAdminReferralOffer');
Route::get('admin/user','AdminController@user');
Route::post('admin/user/update/status','AdminController@updateUserStatus');
Route::post('admin/user/update/note','AdminController@updateUserNote');
Route::get('admin/impersonate/{id}','AdminController@impersonate');

/**Other*/
Route::get('test','TestController@index');
Route::get('test/google','TestController@google');
Route::get('test/pdf','TestController@htmlPdf');
Route::post('test/pdf','TestController@generatePdf');
Route::get('xero','TestController@xero');
Route::get('lynx','TestController@lynx');
Route::get('test-landing','LandingPageController@test');


/**Landing Page*/
Route::get('tradiecrm','LandingPageController@landingPageFor');
Route::get('tradiesoftware','LandingPageController@landingPageFor');
Route::get('crmfortradies','LandingPageController@landingPageFor');
Route::get('crmfortradesmen','LandingPageController@landingPageFor');
Route::get('tradesmencrm','LandingPageController@landingPageFor');
Route::get('tradesmensoftware','LandingPageController@landingPageFor');
Route::get('builderscrm','LandingPageController@landingPageFor');
Route::get('contractorscrm','LandingPageController@landingPageFor');
Route::get('electricianscrm','LandingPageController@landingPageFor');
Route::get('plumberscrm','LandingPageController@landingPageFor');
Route::get('handymancrm','LandingPageController@landingPageFor');
Route::get('contractorsoftware','LandingPageController@landingPageFor');
Route::get('electriciansoftware','LandingPageController@landingPageFor');
Route::get('plumberssoftware','LandingPageController@landingPageFor');
Route::get('hanydmansoftware','LandingPageController@landingPageFor');
Route::get('bestappforbuilders','LandingPageController@landingPageFor');
Route::get('bestcontractorapp','LandingPageController@landingPageFor');
Route::get('bestappfortradies','LandingPageController@landingPageFor');
Route::get('invoiceappforcontractors','LandingPageController@landingPageFor');
Route::get('invoiceappforbuilders','LandingPageController@landingPageFor');
Route::get('invoiceappfortradies','LandingPageController@landingPageFor');
Route::get('electricianapp','LandingPageController@landingPageFor');
Route::get('plumbersapp','LandingPageController@landingPageFor');
Route::get('handymanapp','LandingPageController@landingPageFor');
Route::get('decoratorssoftware','LandingPageController@landingPageFor');

/**Tracking*/
Route::any('track/form','TrackingController@getFormData');

/**TradieDigital Checkout*/
Route::any('api/tradiedigital/checkout/process','LandingPageController@tradieDigitalCheckout');
