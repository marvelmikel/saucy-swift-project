import 'package:flutter/material.dart';
import 'package:flutter_restaurant/helper/responsive_helper.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/provider/auth_provider.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/routes.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:provider/provider.dart';

class SignOutConfirmationDialog extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: Container(
        width: 300,
        child: Consumer<AuthProvider>(builder: (context, auth, child) {
          return Column(mainAxisSize: MainAxisSize.min, children: [

            SizedBox(height: 20),
            CircleAvatar(
              radius: 30,
              backgroundColor: Theme.of(context).primaryColor,
              child: Icon(Icons.contact_support, size: 50),
            ),

            Padding(
              padding: EdgeInsets.all(Dimensions.PADDING_SIZE_LARGE),
              child: Text(getTranslated('want_to_sign_out', context), style: rubikBold, textAlign: TextAlign.center),
            ),

            Container(height: 0.5, color: Theme.of(context).hintColor),

            !auth.isLoading ? Row(children: [

              Expanded(child: InkWell(
                onTap: () {
                  Provider.of<AuthProvider>(context, listen: false).clearSharedData().then((condition) {
                    if(ResponsiveHelper.isWeb()) {
                      Navigator.pushReplacementNamed(context, Routes.getMainRoute());
                    }else {
                      Navigator.pushNamedAndRemoveUntil(context, Routes.getSplashRoute(), (route) => false);
                    }
                  });
                },
                child: Container(
                  padding: EdgeInsets.all(Dimensions.PADDING_SIZE_SMALL),
                  alignment: Alignment.center,
                  decoration: BoxDecoration(borderRadius: BorderRadius.only(bottomLeft: Radius.circular(10))),
                  child: Text(getTranslated('yes', context), style: rubikBold.copyWith(color: Theme.of(context).primaryColor)),
                ),
              )),

              Expanded(child: InkWell(
                onTap: () => Navigator.pop(context),
                child: Container(
                  padding: EdgeInsets.all(Dimensions.PADDING_SIZE_SMALL),
                  alignment: Alignment.center,
                  decoration: BoxDecoration(
                    color: Theme.of(context).primaryColor,
                    borderRadius: BorderRadius.only(bottomRight: Radius.circular(10)),
                  ),
                  child: Text(getTranslated('no', context), style: rubikBold.copyWith(color: Colors.white)),
                ),
              )),

            ]) : Padding(
              padding: EdgeInsets.all(Dimensions.PADDING_SIZE_SMALL),
              child: CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(Theme.of(context).primaryColor)),
            ),
          ]);
        }),
      ),
    );
  }
}
