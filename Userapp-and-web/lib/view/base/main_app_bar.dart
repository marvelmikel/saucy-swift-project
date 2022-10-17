import 'package:flutter/material.dart';
import 'package:flutter_restaurant/provider/splash_provider.dart';
import 'package:flutter_restaurant/utill/images.dart';
import 'package:flutter_restaurant/utill/routes.dart';
import 'package:flutter_restaurant/view/base/menu_bar.dart';
import 'package:provider/provider.dart';

class MainAppBars extends StatelessWidget implements PreferredSizeWidget {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Container(
        color: Theme.of(context).cardColor,
        width: 1170,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: InkWell(
                onTap: () => Navigator.pushNamed(context, Routes.getMainRoute()),
                child: Provider.of<SplashProvider>(context).baseUrls != null?  Consumer<SplashProvider>(
                    builder:(context, splash, child) => FadeInImage.assetNetwork(
                      placeholder: Images.placeholder_rectangle,
                      image:  '${splash.baseUrls.restaurantImageUrl}/${splash.configModel.restaurantLogo}',
                      width: 120, height: 80,
                      imageErrorBuilder: (c, o, s) => Image.asset(Images.placeholder_rectangle, width: 120, height: 80),
                    )): SizedBox(),
              ),
            ),
            MenuBar(true),
          ],
        )
      ),
    );
  }

  @override
  Size get preferredSize => Size(double.maxFinite, 50);
}
