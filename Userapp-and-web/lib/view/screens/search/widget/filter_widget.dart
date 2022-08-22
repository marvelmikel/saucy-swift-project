import 'package:flutter/material.dart';
import 'package:flutter_restaurant/helper/responsive_helper.dart';
import 'package:flutter_restaurant/localization/language_constrants.dart';
import 'package:flutter_restaurant/provider/category_provider.dart';
import 'package:flutter_restaurant/provider/search_provider.dart';
import 'package:flutter_restaurant/utill/color_resources.dart';
import 'package:flutter_restaurant/utill/dimensions.dart';
import 'package:flutter_restaurant/utill/styles.dart';
import 'package:flutter_restaurant/view/base/custom_button.dart';
import 'package:flutter_restaurant/view/screens/home/widget/category_view.dart';
import 'package:provider/provider.dart';

class FilterWidget extends StatelessWidget {
  final double maxValue;
  FilterWidget({@required this.maxValue});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(Dimensions.PADDING_SIZE_LARGE),
      child: Consumer<SearchProvider>(
        builder: (context, searchProvider, child) =>
            SingleChildScrollView(
              scrollDirection: Axis.vertical,
              child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  IconButton(
                    onPressed: () => Navigator.of(context).pop(),
                    icon: Icon(Icons.close, size: 18, color: ColorResources.getGreyBunkerColor(context)),
                  ),
                  Align(
                    alignment: Alignment.center,
                    child: Text(
                      getTranslated('filter', context),
                      textAlign: TextAlign.center,
                      style: Theme.of(context).textTheme.headline3.copyWith(
                        fontSize: Dimensions.FONT_SIZE_LARGE,
                        color: ColorResources.getGreyBunkerColor(context),
                      ),
                    ),
                  ),
                  TextButton(
                    onPressed: () {
                      searchProvider.setRating(-1);
                      Provider.of<CategoryProvider>(context, listen: false).updateSelectCategory(-1);
                      searchProvider.setLowerAndUpperValue(0, 0);
                    },
                    child: Text(
                      getTranslated('reset', context),
                      style: Theme.of(context).textTheme.headline2.copyWith(color: Theme.of(context).primaryColor),
                    ),
                  )
                ],
              ),

              Text(
                getTranslated('price', context),
                style: Theme.of(context).textTheme.headline3,
              ),

              // price range
              RangeSlider(
                values: RangeValues(searchProvider.lowerValue, searchProvider.upperValue),
                max: maxValue,
                min: 0,
                activeColor: Theme.of(context).primaryColor,
                labels: RangeLabels(searchProvider.lowerValue.toString(), searchProvider.upperValue.toString()),
                onChanged: (RangeValues rangeValues) {
                  searchProvider.setLowerAndUpperValue(rangeValues.start, rangeValues.end);
                },
              ),



              Text(
                getTranslated('rating', context),
                style: Theme.of(context).textTheme.headline3,
              ),

              Center(
                child: Container(
                  height: 30,
                  child: ListView.builder(
                    itemCount: 5,
                    shrinkWrap: true,
                    scrollDirection: Axis.horizontal,
                    itemBuilder: (context, index) {
                      return InkWell(
                        child: Icon(
                          searchProvider.rating < (index + 1) ? Icons.star_border : Icons.star,
                          size: 20,
                          color: searchProvider.rating < (index + 1) ? ColorResources.getGreyColor(context) : Theme.of(context).primaryColor,
                        ),
                        onTap: () => searchProvider.setRating(index + 1),
                      );
                    },
                  ),
                ),
              ),
              SizedBox(height: 15),
              Text(
                getTranslated('category', context),
                style: Theme.of(context).textTheme.headline3,
              ),
              SizedBox(height: 13),

              Consumer<CategoryProvider>(
                builder: (context, category, child) {
                  return category.categoryList != null
                      ? GridView.builder(
                        itemCount: category.categoryList.length,
                        padding: EdgeInsets.only(left: Dimensions.PADDING_SIZE_SMALL),
                        physics: BouncingScrollPhysics(),
                        shrinkWrap: true,
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: ResponsiveHelper.isDesktop(context)?4:3,
                            childAspectRatio: 2.0, crossAxisSpacing: 10, mainAxisSpacing: 10),
                        itemBuilder: (context, index) {
                          return InkWell(
                            onTap: () {
                              category.updateSelectCategory(index);
                            },
                            child: Container(
                              alignment: Alignment.center,
                              padding: EdgeInsets.only(right: Dimensions.PADDING_SIZE_SMALL),
                              decoration: BoxDecoration(
                                  border: Border.all(
                                      color: category.selectCategory == index
                                          ? Colors.transparent
                                          : ColorResources.getHintColor(context)),
                                  borderRadius: BorderRadius.circular(7.0),
                                  color: category.selectCategory == index ? Theme.of(context).primaryColor : Colors.transparent),
                              child: Text(
                                category.categoryList[index].name,
                                textAlign: TextAlign.center,
                                style: rubikMedium.copyWith(
                                    fontSize: ResponsiveHelper.isDesktop(context)?Dimensions.FONT_SIZE_DEFAULT: Dimensions.FONT_SIZE_EXTRA_SMALL,
                                    color: category.selectCategory == index ? Colors.white : ColorResources.getHintColor(context)),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          );
                        },
                      )
                      : CategoryShimmer();
                },
              ),
              SizedBox(height: 30),

              CustomButton(
                btnTxt: getTranslated('apply', context),
                onTap: () {
                  searchProvider.sortSearchList(Provider.of<CategoryProvider>(context, listen: false).selectCategory,
                    Provider.of<CategoryProvider>(context, listen: false).categoryList,
                  );

                  Navigator.pop(context);
                },
              )
          ],
        ),
            ),
      ),
    );
  }
}
