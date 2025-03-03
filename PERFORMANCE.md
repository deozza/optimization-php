# Performance improvements

## Introduction

### *Why performance is important?*

Performance is important because it affects directly the user experience. A slow application will make the user feel frustrated, especially on mobile devices. The performance can be either for the loading time of the application or the application itself.

For loading time, if a website takes more than 3 seconds to load, 53% of users will abandon it (<https://www.thinkwithgoogle.com/consumer-insights/consumer-trends/mobile-site-load-time-statistics/>).

For the application itself, if the application is slow, the user will feel frustrated and stop using our application. Our brand image could be affected by this.

## Hypothesis

1. My first hypothesis is the number of nested DB queries in [app/src/Controller/CarouselController.php](/app/src/Controller/CarouselController.php). For each galaxy, we are doing a query to get the every modeles of the galaxy, and for each modeles, we are doing another query to get the files. This is a lot of queries. This has impact on the performance of the database because we potentially have a lot of queries to do, and on the performance of the application because we have 3 nested loops.
2. My second hypothesis is the number of images to load on the client side. If there is a lot of `img` tags not correctly optimized, the browser will load all the full-size images before showing the website. This can have an impact on the performance of the application too because the browser will have to show a lot of images, which is a heavy process.
3. My third hypothesis is the location of the server. It is located in Canada, but maybe the users are in Europe or Asia. This hypothesis needs to be validated because I don't have any data about the location of the users. However, if the users are in Europe or Asia, the initial loading time of the application will be longer because the server is far from the users.

## Tests and measurements

### Tools used

- Symfony debug bar
- Network tab of the Chrome dev tools
- Lighthouse

### Results

1. To confirm my first hypothesis, I can first use the ***debug bar*** of Symfony. I can see the number of DB queries that are done, and the time to create the page. In our case, the number of queries is 164 and the time to create the page (with caches enabled) is 1.5 seconds. The number of queries is very high, so we can improve this, and it will also improve the time to create the page which is pretty high for a cache-enabled page.
2. To confirm my second hypothesis, I can use the ***network tab*** of the dev tools. When I reload the page, I can see the size of the images that are loaded and the time it takes to load them. In our case, there is 121 images for a total of 737 MB to load (overall the website weight 738 MB, so almost all the weight is images). I can also use the **lighthouse** tool to see the performance of the website. In our case, when I run the tool in mobile mode (as it is more exigent), the performance is 41/100. This number itself doesn't mean anything as really big websites have a low score too, but the detailled metrics are interesting. The First Contentful Paint is 2.1 seconds. The Largest Contentful Paint is 139.4 seconds. And the Total Blocking Time is 2.8 seconds. I ran these tests on a local server, so the results will be even worse on a remote server.

## Solutions

### *What immediate programming solutions could fix the application?*

1. To fix the first hypothesis, we can create a custom method in the `GalaxyRepository` that will do a join between the galaxy, the modeles and the files. This way, we will have only one query to do. This will reduce the number of queries and the time to create the page.
2. To fix the second hypothesis, we can optimize the `img` tags. First, we use the `loading="lazy"` attribute to load the images only when they are in the viewport. We can also try to convert the images to `webp` format, which is optimized for the web. Then, we can use the `srcset` attribute to load different sizes of the images depending on the screen size. This way, the browser will load only the images that are needed.

## Conclusion

### New measurements

1. After implementing the first solution, the number of queries is now 1 and the time to create the page varies between 0.1 and 0.5 seconds. This is a huge improvement.
2. After adding the `loading="lazy"` attribute, the initial page load makes 11 requests of images (divided by 11) for a total of 136 MB (divided by 5.5).
