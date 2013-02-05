<?php

          $intCountSponsorship=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship WHERE id_customer=".$intCustomerID);
          $intCountAds=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_ads WHERE id_customer=".$intCustomerID);
          $intCountPayments=$con->GetSQLSingleResult("SELECT count(*) as item FROM sponsorship_payments WHERE id_customer=".$intCustomerID);
          echo '<div class="box">
          <p>
          <small>Sponsorship menu:</small>
          <a href="/sponsorship.html" title="My sponsorships">My sponsorships ('.$intCountSponsorship.')</a>
          &nbsp;&nbsp;|&nbsp;&nbsp;
          <a href="/sponsorship-ads.html" title="My ads">My ads ('.$intCountAds.')</a>
          &nbsp;&nbsp;|&nbsp;&nbsp;
          <a href="/sponsorship-payments.html" title="My payments">My payments ('.$intCountPayments.')</a>
          </p>
          </div>';
?>
