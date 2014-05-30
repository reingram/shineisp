This is the readme.txt file for the iDeal payment gateway for ShineISP.
This gateway is still under development and therefore should not be used for actual payments, only for testing purposes. Using it is at your own risk!

iDeal is the most widely used and most trusted online payment method in The Netherlands. About 80% of all Dutch online shoppers pay by iDeal.
This iDeal payment gateway receives payments through an online payment service provider called TargetPay (https://www.targetpay.com). To use the gateway you will need an account with TargetPay. When creating your account, use promotion code IDEHET to get a discount on transaction fees. You then will pay only € 0,44 per iDeal transaction and no other fees will be charged. Not for maintaining the account, nor for the weekly payments to your bank account.
TargetPay also accepts clients from outside The Netherlands, provided your business is officially registered as a company in your country.

Using the iDeal gateway is fairly simple. You activate it in your ShineISP admin screen, just like any other payment gateway. On the configuration screen for the gateway you fill in:
Name: iDeal
Plugin: Ideal
Account: your own layout code you have received from TargetPay
Payment method: Bank transfer
You can leave both URL's empty. Set to "Active" and "Test" depending on your wishes.
That's it.

ToDo:
- error and cancellation handling
- reporturl to TargetPay
- connecting customer's name to payment
- store correct payment date, not just the number of the month
- dates are now shown as dd/MM/YYYY, not as actual date
- check if the invoice numbering is raised when an order is marked as paid
- and perhaps a few other things