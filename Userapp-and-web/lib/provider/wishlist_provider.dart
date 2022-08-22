import 'package:flutter/material.dart';
import 'package:flutter_restaurant/data/model/response/base/api_response.dart';
import 'package:flutter_restaurant/data/model/response/product_model.dart';
import 'package:flutter_restaurant/data/repository/wishlist_repo.dart';
import 'package:flutter_restaurant/helper/api_checker.dart';
import 'package:provider/provider.dart';

import '../view/base/custom_snackbar.dart';
import 'localization_provider.dart';

class WishListProvider extends ChangeNotifier {
  final WishListRepo wishListRepo;
  WishListProvider({@required this.wishListRepo});

  List<Product> _wishList;
  List<int> _wishIdList = [];

  List<Product> get wishList => _wishList;
  List<int> get wishIdList => _wishIdList;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  void addToWishList(Product product, BuildContext context) async {
    _wishList.add(product);
    _wishIdList.add(product.id);
    notifyListeners();
    ApiResponse apiResponse = await wishListRepo.addWishList(product.id);
    if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
      Map map = apiResponse.response.data;
      String message = map['message'];
      showCustomSnackBar(message, context,isError: false);
    } else {
      _wishList.remove(product);
      _wishIdList.remove(product.id);
      ApiChecker.checkApi(context, apiResponse);
    }
    notifyListeners();
  }

  void removeFromWishList(Product product, BuildContext context) async {
    _wishList.remove(product);
    _wishIdList.remove(product.id);
    notifyListeners();
    ApiResponse apiResponse = await wishListRepo.removeWishList(product.id);
    if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
      String message = 'Removed successfully';
      if(apiResponse.response.data != null) {
        Map map = apiResponse.response.data;
         message = map['message'];
      }

     initWishList(context, Provider.of<LocalizationProvider>(context, listen: false).locale.languageCode,);

      showCustomSnackBar(message, context,isError: false);
    } else {
      _wishList.add(product);
      _wishIdList.add(product.id);
      ApiChecker.checkApi(context, apiResponse);
    }
    notifyListeners();
  }

  Future<void> initWishList(BuildContext context, String languageCode) async {
    _isLoading = true;
    _wishList = [];
    _wishIdList = [];
    ApiResponse apiResponse = await wishListRepo.getWishList(languageCode);
    if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
      _wishList = [];
      _wishList.addAll(ProductModel.fromJson(apiResponse.response.data).products);
      for(int i = 0; i< _wishList.length; i++){
        _wishIdList.add(_wishList[i].id);
      }


    } else {
      ApiChecker.checkApi(context, apiResponse);
    }
    _isLoading = false;
    notifyListeners();
  }
}
