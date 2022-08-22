import 'package:flutter/material.dart';
import 'package:flutter_restaurant/data/model/response/base/api_response.dart';
import 'package:flutter_restaurant/data/model/response/category_model.dart';
import 'package:flutter_restaurant/data/model/response/product_model.dart';
import 'package:flutter_restaurant/data/repository/category_repo.dart';
import 'package:flutter_restaurant/helper/api_checker.dart';
import 'package:flutter_restaurant/view/base/custom_snackbar.dart';

class CategoryProvider extends ChangeNotifier {
  final CategoryRepo categoryRepo;

  CategoryProvider({@required this.categoryRepo});

  List<CategoryModel> _categoryList;
  List<CategoryModel> _subCategoryList;
  List<Product> _categoryProductList;
  bool _pageFirstIndex = true;
  bool _pageLastIndex = false;
  bool _isLoading = false;

  List<CategoryModel> get categoryList => _categoryList;
  List<CategoryModel> get subCategoryList => _subCategoryList;
  List<Product> get categoryProductList => _categoryProductList;
  bool get pageFirstIndex => _pageFirstIndex;
  bool get pageLastIndex => _pageLastIndex;
  bool get isLoading => _isLoading;

  Future<void> getCategoryList(BuildContext context, bool reload, String languageCode) async {
    _subCategoryList = null;
    if(_categoryList == null || reload) {
      _isLoading = true;
      ApiResponse apiResponse = await categoryRepo.getCategoryList(languageCode);
      if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
        _categoryList = [];
        apiResponse.response.data.forEach((category) => _categoryList.add(CategoryModel.fromJson(category)));
      } else {
        ApiChecker.checkApi(context, apiResponse);
      }
      _isLoading = false;
      notifyListeners();
    }
  }

  void getSubCategoryList(BuildContext context, String categoryID, String languageCode) async {
    _subCategoryList = null;
    _isLoading = true;
    ApiResponse apiResponse = await categoryRepo.getSubCategoryList(categoryID, languageCode);
    if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
      _subCategoryList= [];
      apiResponse.response.data.forEach((category) => _subCategoryList.add(CategoryModel.fromJson(category)));
      getCategoryProductList(context, categoryID,languageCode);
    } else {
      ApiChecker.checkApi(context, apiResponse);
    }
    _isLoading = false;
    notifyListeners();
  }

  void getCategoryProductList(BuildContext context, String categoryID, String languageCode) async {
    _categoryProductList = null;
    notifyListeners();
    ApiResponse apiResponse = await categoryRepo.getCategoryProductList(categoryID, languageCode);
    if (apiResponse.response != null && apiResponse.response.statusCode == 200) {
      _categoryProductList = [];
      apiResponse.response.data.forEach((category) => _categoryProductList.add(Product.fromJson(category)));
      notifyListeners();
    } else {
      showCustomSnackBar(apiResponse.error.toString(), context);
    }
  }

  int _selectCategory = -1;

  int get selectCategory => _selectCategory;

  updateSelectCategory(int index) {
    _selectCategory = index;
    notifyListeners();
  }
  updateProductCurrentIndex(int index, int totalLength) {
    if(index > 0) {
      _pageFirstIndex = false;
      notifyListeners();
    }else{
      _pageFirstIndex = true;
      notifyListeners();
    }
    if(index + 1  == totalLength) {
      _pageLastIndex = true;
      notifyListeners();
    }else {
      _pageLastIndex = false;
      notifyListeners();
    }
  }
}
