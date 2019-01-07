<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define('DB_NAME', 'pqhuy198_wordpress');

/** Username của database */
define('DB_USER', 'pqhuy198_wp');

/** Mật khẩu của database */
define('DB_PASSWORD', '12345');

/** Hostname của database */
define('DB_HOST', '192.185.41.153');

/** Database charset sử dụng để tạo bảng database. */
define('DB_CHARSET', 'utf8mb4');

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'EB@bUJx,w6LZ;BD3fWRaNZO^Q?+d eIA@QqU`$sIirekXTU G.;=_w;eo2V1O]I{');
define('SECURE_AUTH_KEY',  '!y=BlNyL?D>.k]k>xE{h{O$/KI1<J<.6#^O4,`Vh7^`~xtdlF4~NT[l`F^y)HP8a');
define('LOGGED_IN_KEY',    ' f=7r+h)unh{?[!zD8pUM5Ysb8$9E8~w)u?DnoWy8=ps|c{5M<qF8cV&i|@M)<$p');
define('NONCE_KEY',        'Q.y5?qP.;|qB!5gcTd-J+!AN!/{j6O@8fbV=^2Zvs-Ufu^|~y,^&Vv3~:33SYlGV');
define('AUTH_SALT',        ' 9Obd.lvnEs(ahSz#-8{3t8cwVz;>#zgy$|N-B<{UpKB;v{u$wnsX5<<k57h1Nt`');
define('SECURE_AUTH_SALT', '~DYS2dN#<r.opRCH7PAT9V^$ 0QeC_<b%U$$o}+]i{>5LW^!k4/Chz^:U9SfL3{r');
define('LOGGED_IN_SALT',   '(t;ff)_=j.9=7r/xzBC+3pEnj}I!8BBFqpdf6nk^6~24:^65w,@F59dKO]<)$$|s');
define('NONCE_SALT',       'CdY=^nogWv(]Sph#xr1QBp!._k~[g1m9ng6%zi$G>k 1QtyC}[vb:i?RS5~z_&v*');

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
