jQuery(document).ready(function ($) {
  // Utility functions
  function showStatus($status, message) {
    $status.text(message).show();
  }

  function showError($error, message) {
    $error.text(message).show();
  }

  function hideAndResetProgress($progressBar, $progressInner) {
    $progressBar.hide();
    $progressInner.css("width", "0%");
  }

  function createRedirectButton(url, $status) {
    const $redirect_button = $(
      '<button class="migration-redirect-button">View Migrated Stories</button>'
    );
    $status.after($redirect_button);
    $redirect_button
      .hide()
      .fadeIn(600)
      .on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        window.location.href = url;
      });
  }

  $(document).on("click", ".ctl-migrate", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $button = $(this);
    const $progressBar = $(".ctl-progress-bar");
    const $progressInner = $(".ctl-progress-bar-inner");
    const $status = $('<div class="migration-status">Preparing migration...</div>');
    const $error = $('<div class="migration-error"></div>');

    $(".migration-status, .migration-error").remove();
    $progressBar.after($status).after($error);
    $progressBar.show();

    let progress = 0;
    let interval = setInterval(function () {
      if (progress < 90) {
        progress += 2;
        $progressInner.css("width", progress + "%");
        showStatus($status, `Migration in progress... ${progress}%`);
      } else {
        clearInterval(interval);
      }
    }, 200);

    $button.prop("disabled", true).text("Migrating...");

    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "ctl_migrate_stories",
        nonce: ctl_migration.nonce,
      },
      success: function (response) {
        $progressInner.css("width", "100%");
        progress = 100;
        showStatus($status, `Migration in progress... 100%`);
        clearInterval(interval);

        if (response.success) {
          const totalStories = parseInt(response.data.total_stories) || 0;
          const message = (response.data.message || "").trim().toLowerCase();

          if (totalStories === 0) {
            showStatus($status, response.data.message);
            if (message === "no attachment found to migrate.") {
              hideAndResetProgress($progressBar, $progressInner);
            }
          } else {
            $status
              .hide()
              .html(
                `🎉 Migration completed successfully! ${totalStories} announcements have been migrated.`
              )
              .fadeIn(600);
          }

          if (ctl_migration && ctl_migration.redirect_url) {
            createRedirectButton(ctl_migration.redirect_url, $status);
          } else {
            showError($error, "Error: Redirect URL not found");
          }

          $button.css("display", "none");
        } else {
          const errorMessage =
            response.data && response.data.message
              ? response.data.message
              : "Migration failed. Please try again.";
          showError($error, errorMessage);
          $status.hide();
          $button.prop("disabled", false).text("Migrate Content");
          hideAndResetProgress($progressBar, $progressInner);
        }
      },
      error: function () {
        clearInterval(interval);
        showError($error, "An error occurred during migration. Please try again.");
        $button.prop("disabled", false).text("Migrate Content");
        hideAndResetProgress($progressBar, $progressInner);
      },
    });
  });
});
