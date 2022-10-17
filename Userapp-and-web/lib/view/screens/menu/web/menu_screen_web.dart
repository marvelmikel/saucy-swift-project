import 'package:flutter/material.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/provider/profile_provider.dart';
import 'package:flutter_restaurant/provider/splash_provider.dart';
import 'package:flutter_restaurant/utill/color_resources.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/images.dart';
import 'package:flutter_restaurant/utill/routes.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:flutter_restaurant/view/base/footer_view.dart';
import 'package:flutter_restaurant/view/screens/menu/web/menu_item_web.dart';
import 'package:provider/provider.dart';

import '../../../../data/model/response/menu_model.dart';
import '../../../../provider/auth_provider.dart';
import '../../../base/custom_dialog.dart';

class MenuScreenWeb extends StatelessWidget {
  @override
  Widget build(BuildContext context) {

    final _splashProvider =  Provider.of<SplashProvider>(context, listen: false);
    final bool _isLoggedIn = Provider.of<AuthProvider>(context, listen: false).isLoggedIn();

    final List<MenuModel> _menuList = [
      MenuModel(icon: Images.order, title: getTranslated('my_order', context), route:  Routes.getDashboardRoute('order')),
      MenuModel(icon: Images.profile, title: getTranslated('profile', context), route: Routes.getProfileRoute()),
      MenuModel(icon: Images.location, title: getTranslated('address', context), route: Routes.getAddressRoute()),
      MenuModel(icon: Images.message, title: getTranslated('message', context), route: Routes.getChatRoute(orderModel: null)),
      MenuModel(icon: Images.coupon, title: getTranslated('coupon', context), route: Routes.getCouponRoute()),
      MenuModel(icon: Images.notification, title: getTranslated('notification', context), route: Routes.getNotificationRoute()),
      MenuModel(icon: Images.help_support, title: getTranslated('help_and_support', context), route: Routes.getSupportRoute()),
      MenuModel(icon: Images.privacy_policy, title: getTranslated('privacy_policy', context), route: Routes.getPolicyRoute()),
      MenuModel(icon: Images.terms_and_condition, title: getTranslated('terms_and_condition', context), route:Routes.getTermsRoute()),

      if(_splashProvider.policyModel != null
          && _splashProvider.policyModel.refundPage != null
          && _splashProvider.policyModel.refundPage.status
      ) MenuModel(icon: Images.refundPolicy, title: getTranslated('refund_policy', context), route: Routes.getRefundPolicyRoute()),

      if(_splashProvider.policyModel != null
          && _splashProvider.policyModel.returnPage != null
          && _splashProvider.policyModel.returnPage.status
      ) MenuModel(icon: Images.returnPolicy, title: getTranslated('return_policy', context), route: Routes.getReturnPolicyRoute()),

      if(_splashProvider.policyModel != null
          && _splashProvider.policyModel.cancellationPage != null
          && _splashProvider.policyModel.cancellationPage.status
      ) MenuModel(icon: Images.cancellationPolicy, title: getTranslated('cancellation_policy', context), route: Routes.getCancellationPolicyRoute()),

      MenuModel(icon: Images.about_us, title: getTranslated('about_us', context), route: Routes.getAboutUsRoute()),

      MenuModel(
        icon: Images.version,
        title: "${getTranslated('version', context)} ${Provider.of<SplashProvider>(context, listen: false).configModel.softwareVersion ?? ''}",
        route: 'version',
      ),

      MenuModel(icon: Images.login, title: getTranslated(_isLoggedIn ? 'logout' : 'login', context), route: 'auth'),

    ];

    return SingleChildScrollView(
      child: Column(children: [
          Center(child: Consumer<ProfileProvider>(builder: (context, profileProvider, child) {
            return SizedBox(width: 1170, child: Stack(children: [

              Column(children: [
                Container(
                  height: 150,  color:  ColorResources.getProfileMenuHeaderColor(context),
                  alignment: Alignment.centerLeft,
                  padding: EdgeInsets.symmetric(horizontal: 240.0),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _isLoggedIn ? profileProvider.userInfoModel != null ? Text(
                        '${profileProvider.userInfoModel.fName ?? ''} ${profileProvider.userInfoModel.lName ?? ''}',
                        style: robotoRegular.copyWith(fontSize: Dimensions.FONT_SIZE_EXTRA_LARGE, color: ColorResources.getWhiteAndBlack(context)),
                      ) : SizedBox(height: Dimensions.PADDING_SIZE_DEFAULT, width: 150) : Text(
                        getTranslated('guest', context),
                        style: rubikRegular.copyWith(fontSize: Dimensions.FONT_SIZE_EXTRA_LARGE, color: ColorResources.getWhiteAndBlack(context)),
                      ),
                      SizedBox(height: Dimensions.PADDING_SIZE_SMALL),
                      _isLoggedIn ? profileProvider.userInfoModel != null ? Text(
                        '${profileProvider.userInfoModel.email ?? ''}',
                        style: robotoRegular.copyWith(color: ColorResources.getWhiteAndBlack(context)),
                      ) : SizedBox(height: 15, width: 100) : Text(
                        'demo@demo.com',
                        style: rubikRegular.copyWith(fontSize: Dimensions.FONT_SIZE_EXTRA_LARGE, color: ColorResources.getWhiteAndBlack(context)),
                      ),
                      SizedBox(height: Dimensions.PADDING_SIZE_SMALL),
                      _isLoggedIn ? profileProvider.userInfoModel != null ? Text(
                        '${getTranslated('points', context)}: ${profileProvider.userInfoModel.point ?? ''}',
                        style: rubikRegular.copyWith(color: ColorResources.getWhiteAndBlack(context)),
                      ) : Container(height: 15, width: 100, color: Colors.white) : SizedBox(),

                    ],
                  ),

                ),

                SizedBox(height: 100),

                GridView.builder(
                  physics: NeverScrollableScrollPhysics(),
                  shrinkWrap: true,
                  gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 6,
                    crossAxisSpacing: Dimensions.PADDING_SIZE_EXTRA_LARGE,
                    mainAxisSpacing: Dimensions.PADDING_SIZE_EXTRA_LARGE,
                  ),
                  itemCount: _menuList.length,
                  itemBuilder: (context, index) {
                    return MenuItemWeb(
                      routeName: _menuList[index].route,
                      title: _menuList[index].title,
                      image: _menuList[index].icon,
                    );
                  },
                ),

                SizedBox(height: Dimensions.PADDING_SIZE_DEFAULT),

              ],),


              Positioned(left: 30, top: 45, child: Builder(builder: (context) {
                return Container(
                  height: 180, width: 180,
                  decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 4),
                      boxShadow: [BoxShadow(color: Colors.white.withOpacity(0.1), blurRadius: 22, offset: Offset(0, 8.8) )]),
                  child: ClipOval(
                    child: _isLoggedIn ? FadeInImage.assetNetwork(
                      placeholder: Images.placeholder_user, height: 170, width: 170, fit: BoxFit.cover,
                      image: '${Provider.of<SplashProvider>(context, listen: false).baseUrls.customerImageUrl}/'
                          '${profileProvider.userInfoModel != null ? profileProvider.userInfoModel.image : ''}',
                      // imageErrorBuilder: (c, o, s) => Image.asset(Images.placeholder_user, height: 170, width: 170, fit: BoxFit.cover),
                    ) : Image.asset(Images.placeholder_user, height: 170, width: 170, fit: BoxFit.cover),
                  ),
                );
              }),),

              Positioned(right: 0, top: 140, child: _isLoggedIn ? Padding(
                padding: const EdgeInsets.all(Dimensions.PADDING_SIZE_DEFAULT),
                child: InkWell(
                  onTap: (){
                    showAnimatedDialog(context, Consumer<AuthProvider>(builder: (context, authProvider, _) {
                      return authProvider.isLoading ? Center(child: CircularProgressIndicator()) : CustomDialog(
                        icon: Icons.question_mark_sharp,
                        title: getTranslated('are_you_sure_to_delete_account', context),
                        description: getTranslated('it_will_remove_your_all_information', context),
                        buttonTextTrue: getTranslated('yes', context),
                        buttonTextFalse: getTranslated('no', context),
                        onTapTrue: () => Provider.of<AuthProvider>(context, listen: false).deleteUser(context),
                        onTapFalse: () => Navigator.of(context).pop(),
                      );
                    }),
                    dismissible: false,
                    isFlip: true,
                  );},
                  child: Row(children: [
                    Padding(padding: const EdgeInsets.symmetric(horizontal: Dimensions.PADDING_SIZE_EXTRA_SMALL),
                      child: Icon(Icons.delete, color: Theme.of(context).primaryColor, size: 16),
                    ),

                    Padding(padding: const EdgeInsets.symmetric(horizontal: Dimensions.PADDING_SIZE_EXTRA_SMALL),
                      child: Text(getTranslated('delete_account', context)),
                    ),
                  ],),
                ),
              ) : SizedBox(),),
            ],),
            );
          }),
          ),

          FooterView(),
        ],
      ),
    );
  }
}
