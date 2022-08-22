
import 'package:flutter/material.dart';
import 'package:flutter_restaurant/data/model/response/address_model.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/provider/location_provider.dart';
import 'package:flutter_restaurant/utill/color_resources.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/images.dart';
import 'package:flutter_restaurant/utill/routes.dart';
import 'package:flutter_restaurant/view/screens/address/widget/deleteConfirmation_dialog.dart';
import 'package:provider/provider.dart';

class AddressWidget extends StatelessWidget {
  final AddressModel addressModel;
  final int index;
  AddressWidget({@required this.addressModel, @required this.index});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: Dimensions.PADDING_SIZE_SMALL),
      child: InkWell(
        onTap: () {
          if(addressModel != null) {
            Navigator.pushNamed(context, Routes.getMapRoute(addressModel));
          }
        },
        child: Container(
          padding: EdgeInsets.all(Dimensions.PADDING_SIZE_SMALL),
          height: 70,
          decoration: BoxDecoration(borderRadius: BorderRadius.circular(Dimensions.PADDING_SIZE_SMALL), color: ColorResources.getSearchBg(context)),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                flex: 2,
                child: Row(
                  children: [
                    SizedBox(width: Dimensions.PADDING_SIZE_SMALL),
                    Icon(
                      addressModel.addressType.toLowerCase() == "home"
                          ? Icons.home_filled
                          : addressModel.addressType.toLowerCase() == "workplace"
                          ? Icons.work_outline
                          : Icons.list_alt_outlined,
                      //color: Theme.of(context).textTheme.bodyText1.color.withOpacity(.45),
                      size: 22,
                    ),
                    SizedBox(width: Dimensions.PADDING_SIZE_DEFAULT),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text(
                            addressModel.addressType,
                            style: Theme.of(context).textTheme.headline2.copyWith(color: Theme.of(context).textTheme.bodyText1.color.withOpacity(.65)),
                          ),
                          Text(
                            addressModel.address,
                            maxLines: 1, overflow: TextOverflow.ellipsis,
                            style: Theme.of(context).textTheme.headline2.copyWith(color: Theme.of(context).textTheme.bodyText1.color),
                          ),
                        ],
                      ),
                    ),
                    SizedBox(width: 5),
                  ],
                ),
              ),
              Stack(
                clipBehavior: Clip.none, children: [
                  Container(
                    margin: EdgeInsets.only(right: 10),
                    child: Container(
                      width: 40,
                      height: 40,
                      margin: EdgeInsets.only(right: 20),
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(5),
                        color: Theme.of(context).cardColor,
                        border: Border.all(width: 1, color: ColorResources.getGreyColor(context)),
                      ),
                      child: Icon(Icons.map),
                    ),
                  ),
                  //SizedBox(width: 9.0),
                  // Image.asset(Images.menu)
                  Positioned(
                    right: -10, top: 0, bottom: 0,
                    child: Material(
                      color: Colors.transparent,
                      child: PopupMenuButton<String>(
                        icon: Image.asset(Images.three_dot, width: Dimensions.PADDING_SIZE_LARGE),
                        padding: EdgeInsets.all(0),
                        onSelected: (String result) {
                          if (result == 'delete') {
                            showDialog(context: context, barrierDismissible: false, builder: (context) => DeleteConfirmationDialog(addressModel: addressModel,index: index));
                          } else {
                            Provider.of<LocationProvider>(context, listen: false).updateAddressStatusMessage(message: '');
                            Navigator.pushNamed(
                              context,
                              Routes.getAddAddressRoute('address', 'update', addressModel),
                            );
                          }
                        },
                        itemBuilder: (BuildContext c) => <PopupMenuEntry<String>>[
                          PopupMenuItem<String>(
                            value: 'edit',
                            child: Text(getTranslated('edit', context), style: Theme.of(context).textTheme.headline2),
                          ),
                          PopupMenuItem<String>(
                            value: 'delete',
                            child: Text(getTranslated('delete', context), style: Theme.of(context).textTheme.headline2),
                          ),
                        ],
                      ),
                    ),
                  )
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
