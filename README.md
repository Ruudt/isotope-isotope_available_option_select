Define that some options of an attribute are not available per product
======================================================================

This extension adds an extra field together with each attribute that allows to select which options are available for a certain product. This way options can be enabled and disabled on product basis. So an attribute with 20 options can be selected to show only 5 options for a specific product.

Example:
Attribute color: red, blue, green, black, white. (does not change product price)

 * Product 1 is available in all colors
 * Product 2 is available in all colors except red and green

This is impossible without variants in Isotope, but this extension adds the possibility.

**But then why use this extension** instead of variants which seem to do the exact same?
In short: use this extension when your attribute (for example color) has 20 options but the product you sell comes in only 5 of them and the choice of color does not affect the price (or use isotope_attribute_price if it does) because variations are a lot of extra work to create and maintain.

More detailed: You'd use variants in normal circumstances because this enables you to define exactly which combination of attribute choices are available (for example: Jacket in "Brown Leather Large", "Brown Leather Medium", "Black Leather Medium") in this case only 3 combinations of color, fabric and size exist. But if you cary a product with these attributes having every possible combination of 5 colors, 2 fabrics and 5 sizes you have 5x2x5=50 variations to maintain, if all of the combinations have the same price there is no need for variations if the normal attribute could be set to show oly some of all options per product. That last thing is what this extension does.

Together with the extension mentioned above this adds flexibility to the way products can be managed. This working method is also available in Magento next to variations, so I expect the extra functionality to be added to Isotope at some point.
