# Introduction :
The importance of optimization has been shown by the decline in users after the rewrite. A page that loads slowly leads to the frustration of the user, who then becomes likely to leave the page before it's even loaded. According to studies, better optimization leads to increase in revenue, and in visit rate.
# Hypothesis :
Main problem is the sheer number of images on the page combined with their format slows down the loading of the page as media files are the longest files to charge for the browser.
There are too many requests.
There's no page caching as well.
Natural SEO on the website could be improved and could explain why there's fewer users.
# Solutions :
-Compressing images and converting them to .webp (they're all .jpg)
-Changing the architecture of the frontend to SSR to lower the amount of requests
-Putting in place a browser cache
-Improving SEO with IMG alts and such
# Conclusion :
In the future, perhaps changing the way images are stored would help avoiding these performances issues. Changing to location of the VPS to be closer to most users would also help performance.