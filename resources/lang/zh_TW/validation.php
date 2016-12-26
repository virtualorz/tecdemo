<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
     */

    'accepted' => '您必須允許 :attribute 的內容.',
    'active_url' => ':attribute 必須是有效的網址.',
    'after' => ':attribute 日期必須在 :date 日期之後.',
    'after_equal' => ':attribute 日期必須在 :date 日期之後或相同.',
    'alpha' => ':attribute 必須是英文字.',
    'alpha_dash' => ':attribute 必須是英文、數字、破折號( - )、底線( _ ).',
    'alpha_num' => ':attribute 必須是英文、數字.',
    'array' => ':attribute 必須是陣列.',
    'before' => ':attribute 日期必須在 :date 日期之前.',
    'before_equal' => ':attribute 日期必須在 :date 日期之前或相同.',
    'between' => [
        'numeric' => ':attribute 必須是介於 :min 和 :max 的數字.',
        'file' => ':attribute 必須是介於 :min 和 :max KB 的檔案.',
        'string' => ':attribute 必須是介於 :min 和 :max 字元數的字串.',
        'array' => ':attribute 必須是包含 :min 和 :max 元素的陣列.',
    ],
    'boolean' => ':attribute 必須是布林.',
    'confirmed' => ':attribute 必須與再次確認值相同.',
    'date' => ':attribute 必須是日期.',
    'date_format' => ':attribute 日期格式與 :format 不相符.',
    'different' => ':attribute 欄位值必須與 :other 相同.',
    'digits' => ':attribute 必須是 :digits位數字.',
    'digits_between' => ':attribute 必須是 :min 到 :max位數字.',
    'email' => ':attribute 必須是合法的E-mail格式.',
    'filled' => ':attribute 必填.',
    'exists' => ':attribute 選擇的項目不合法.',
    'image' => ':attribute 必須是圖片.',
    'in' => ':attribute 選擇的項目不合法.',
    'integer' => ':attribute 必須是整數.',
    'ip' => ':attribute 必須是合法的IP格式.',
    'json' => ':attribute 必須是合法的Json格式.',
    'json_file' => [
        'format' => ':attribute json 格式錯誤.',
        'required' => ':attribute 請上傳檔案.',
        'min' => ':attribute 請上傳最少 :min 個檔案.',
        'max' => ':attribute 請上傳最多 :max 個檔案.',
    ],
    'json_editor' => [
        'format' => ':attribute json 格式錯誤.',
        'required' => ':attribute 必填.',
    ],
    'max' => [
        'numeric' => ':attribute 不可大於 :max.',
        'file' => ':attribute 不可大於 :max KB.',
        'string' => ':attribute 不可大於 :max 個字.',
        'array' => ':attribute 不可包含大於 :max 個元素.',
    ],
    'mimes' => ':attribute 必須是 :values 類型的檔案.',
    'min' => [
        'numeric' => ':attribute 不可小於 :min.',
        'file' => ':attribute 不可小於 :min KB.',
        'string' => ':attribute 不可小於 :min 個字.',
        'array' => ':attribute 不可包含小於 :min 個元素.',
    ],
    'not_in' => ':attribute 選擇的項目不合法.',
    'numeric' => ':attribute 必須是數值.',
    'regex' => ':attribute 格式不合法.',
    'required' => ':attribute 必填.',
    'required_if' => '當 :other 是 :value 則 :attribute 必填.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => ':attribute 和 :other 必須相同.',
    'size' => [
        'numeric' => ':attribute 必須是 :size.',
        'file' => ':attribute 必須 :size KB.',
        'string' => ':attribute 必須 :size 字元.',
        'array' => ':attribute 必須包含 :size 元素.',
    ],
    'string' => ':attribute 必須是字串.',
    'timezone' => ':attribute 必須是合法的時區.',
    'unique' => ':attribute 資料已被使用.',
    'url' => ':attribute 必須是合法的網址格式.',
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | Here you may specify custom validation messages for attributes using the
      | convention "attribute.rule" to name the lines. This makes it quick to
      | specify a specific custom language line for a given attribute rule.
      |
     */
    'custom' => [
        'course_class-date_start' => [
            'required_if' => ':attribute 必填',
        ],
        'course_class-date_end' => [
            'required_if' => ':attribute 必填',
        ],
    ],
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
     */
    'attributes' => [
        #
        #aboutus_advantage
        'aboutus_advantage-content' => '編輯器內容',
        #
        #aboutus_award
        'aboutus_award-content' => '編輯器內容',
        #
        #aboutus_partner
        'aboutus_partner-aboutus_partner_cate_id' => '分類',
        'aboutus_partner-name' => '名稱',
        'aboutus_partner-content' => '編輯器內容',
        #
        #aboutus_teacher
        'aboutus_teacher-name_cht' => '中文姓名',
        'aboutus_teacher-name_eng' => '英文姓名',
        'aboutus_teacher-title' => '職稱',
        'aboutus_teacher-pro_title' => '專業講師職稱',
        'aboutus_teacher-cert' => '認證',
        'aboutus_teacher-skill' => '專長',
        'aboutus_teacher-photo' => '照片',
        'aboutus_teacher-file' => '講師經歷',
        #
        #aboutus_teacher_cert
        'aboutus_teacher_cert-course_cert_id' => '認證',
        #
        #aboutus_test
        'aboutus_test-content' => '編輯器內容',
        #
        #aboutus_tp
        'aboutus_tp-content' => '編輯器內容',
        #
        #aboutus_training
        'aboutus_training-content' => '編輯器內容',
        #activity
        'activity_date' => '活動日期',
        'activity_name' => '活動名稱',
        'activity_id' => '活動編號',
        'activity_type' => '活動類型',
        'activity_content' => '活動說明',
        #
        #admin
        'admin' =>'管理員',
        'admin_add' => '已加入管理員',
        'admin-account' => '帳號',
        'admin-password' => '密碼',
        'admin-password_confirmation' => '確認密碼',
        'admin-name' => '姓名',
        'admin-title' => '職稱',
        'admin-phone' => '公司電話',
        'admin-email' => 'E-mail',
        'admin-address' => '地址',
        'admin-permission' => '權限',
        'admin-apply_mail' => '收取報名信',
        #
        #admin_export
        'admin_export-created_at' => '匯出時間',
        'admin_export-admin_account' => '匯出人員帳號',
        'admin_export-admin_name' => '匯出人員姓名',
        'admin_export-ip' => '匯出IP',
        'admin_export-type' => '匯出資料',
        'admin_export-message' => '匯出訊息',
        'address' => '地址',
        #
        #apply
        'apply-comment' => '意見',
        'apply-agree' => '我同意以下條款',
        'apply-total_fee' => '總金額',
        'apply_date' => '申請日期',
        'apply_name' => '申請人',
        'apply_reason' => '申請理由',
        #
        #apply_item
        'apply_item-create_admin_id' => '報名人員',
        'apply_item-fee' => '費用',
        'apply_item-apply_at' => '購買日期',
        'apply_item-pay_way' => '繳費方式',
        'apply_item-ticket_class' => '上課券號碼',
        'apply_item-ticket_point' => '點券號碼',
        'apply_item-is_pay' => '繳費狀況',
        'apply_item-course' => '購買課程',
        'apply_item-count' => '報名人數',
        #
        #attend
        'attend_count' => '報到人數',
        #
        #check
        'check_out' => '付款方式',
        #
        #card_id_number
        'card_id_number' => '證號',
        #
        #company
        'company' => '公司',
        #
        #company
        'contact' => '聯絡人',
        #
        #contact_ask_course_paper
        'contact_ask_course_paper-name_cht' => '中文姓名',
        'contact_ask_course_paper-work_name' => '公司名稱/學校名稱',
        'contact_ask_course_paper-title' => '職稱',
        'contact_ask_course_paper-town_id' => '聯絡地址區域',
        'contact_ask_course_paper-address' => '聯絡地址',
        'contact_ask_course_paper-email' => 'E-mail',
        'contact_ask_course_paper-phone' => '電話',
        'contact_ask_course_paper-phone_ext' => '分機',
        'contact_ask_course_paper-cellphone' => '行動電話',
        'contact_ask_course_paper-is_join' => '是否參加過TP課程',
        #
        #contact_ask_course_price
        'contact_ask_course_price-name_cht' => '中文姓名',
        'contact_ask_course_price-work_name' => '公司名稱/學校名稱',
        'contact_ask_course_price-title' => '職稱',
        'contact_ask_course_price-email' => 'E-mail',
        'contact_ask_course_price-phone' => '電話',
        'contact_ask_course_price-phone_ext' => '分機',
        'contact_ask_course_price-cellphone' => '行動電話',
        'contact_ask_course_price-know_from' => '您從哪邊得知Training Partners?',
        'contact_ask_course_price-know_from_etc' => '其他',
        'contact_ask_course_price-course_id' => '欲索取報價課程名稱',
        #
        #contact_location
        'contact_location-content' => '編輯器內容',
        #
        #contact_order_epaper
        'contact_order_epaper-name_cht' => '中文姓名',
        'contact_order_epaper-work_name' => '公司名稱/學校名稱',
        'contact_order_epaper-title' => '職稱',
        'contact_order_epaper-town_id' => '聯絡地址區域',
        'contact_order_epaper-address' => '聯絡地址',
        'contact_order_epaper-email' => 'E-mail',
        'contact_order_epaper-phone' => '電話',
        'contact_order_epaper-phone_ext' => '分機',
        'contact_order_epaper-cellphone' => '行動電話',
        'contact_order_epaper-is_join' => '是否參加過TP課程',
        #
        #contact_us
        'contact_us-name_cht' => '中文姓名',
        'contact_us-work_name' => '公司名稱/學校名稱',
        'contact_us-title' => '職稱',
        'contact_us-email' => 'E-mail',
        'contact_us-phone' => '電話',
        'contact_us-phone_ext' => '分機',
        'contact_us-cellphone' => '行動電話',
        'contact_us-message' => '聯絡訊息',
        #content
        'content' => '內容',
        #
        #course
        'course-course_cert_id' => '認證',
        'course-course_cert_id_2' => '認證名稱(Certificate)',
        'course-code' => '代碼',
        'course-name' => '課程名稱',
        'course-name_cht' => '中文名稱',
        'course-name_eng' => '英文名稱',
        'course-duration' => '課程長度',
        'course-duration_type' => '課程長度單位',
        'course-class_time' => '上課時間',
        'course-fee' => '費用',
        'course-point' => '點數',
        'course-teaching' => '教材',
        'course-test_code' => '考試代碼',
        'course-goal' => '課程目標',
        'course-target' => '適合對象',
        'course-content' => '課程內容',
        'course-basic' => '學前基礎',
        'course-is_chosen' => '是否精選',
        'course-chosen_content' => '精選內容',
        'course-course_code' => '課程代碼',
        'course-course_name' => '課程名稱',
        'course-count_class' => '排課次數',
        'course-count_class_success' => '成功開課次數',
        'course-count_class_delay' => '延遲次數',
        'course-count_class_member' => '已上課人次',
        'course-courset_class' => '課程時間',
        #
        #courseinfo_cert
        'courseinfo_cert-content' => '編輯器內容',
        #
        #courseinfo_recommend
        'courseinfo_recommend-member_name' => '學員姓名',
        'courseinfo_recommend-member_name_cht' => '學員姓名(中文)',
        'courseinfo_recommend-member_name_eng' => '學員姓名(英文)',
        'courseinfo_recommend-course_class_id' => '推薦課程開班',
        'courseinfo_recommend-course_class_id2' => '開班時間',
        'courseinfo_recommend-aboutus_teacher_id' => '推薦講師',
        'courseinfo_recommend-content' => '心得與感想',
        'courseinfo_recommend-photo' => '照片',
        'courseinfo_recommend-url' => '相關連結',
        'courseinfo_recommend-course_partner' => '推薦課程原廠',
        'courseinfo_recommend-course_cert' => '推薦課程認證',
        'courseinfo_recommend-course' => '推薦課程',
        #
        #course_cert
        'course_cert-course_partner_id' => '原廠',
        'course_cert-course_partner_id_2' => '原廠廠商(Vendor)',
        'course_cert-name' => '名稱',
        #
        #course_class
        'course_class-id' => '課程開班',
        'course_class-course_id' => '課程',
        'course_class-course_class_locale_id' => '上課地點',
        'course_class-class_way' => '上課方式',
        'course_class-class_time' => '上課時間',
        'course_class-date_unlimited' => '暫無開課日期',
        'course_class-date' => '上課日期',
        'course_class-date_start' => '上課開始日期',
        'course_class-date_end' => '上課結束日期',
        'course_class-quota' => '名額',
        'course_class-status' => '開課狀態',
        'course_class-notice' => '通知狀態',
        'course_class-notice_dt' => '通知日期',
        'course_class-notice_admin_id' => '通知人員',
        'course_class-date_suspend' => '停課日期',
        'course_class-delay_course_class_id' => '課程延課',
        'course_class-class_time2' => '班別',
        'course_class-date_start_from' => '原課程時間',
        'course_class-date_start_delay_to' => '延課至',
        #
        #course_class_locale
        'course_class_locale-name' => '名稱',
        'course_class_locale-address' => '地址',
        'course_class_locale-traffic' => '交通資訊',
        #
        #course_class_suspend
        'course_class_suspend-id' => '停課日期系統編號',
        'course_class_suspend-date' => '停課日期',
        #
        #course_partner
        'course_partner-name' => '名稱',
        'contact' => '聯絡方式',
        #
        #design_type
        'design_type' => '設計類型',
        #
        #dt
        'dt-start' => '開始日期',
        'dt-end' => '結束日期',
        #
        #
        #email
        'email' => 'email',
        #
        #edm
        'edm-name' => '名稱',
        'edm-content' => '編輯器內容',
        'edm-url' => '網址',
        #
        #end_dt
        'end_dt' => '結束日期',
        'end_time' => '結束時間',
        #
        #execute
        'execute_time' => '執行日期',
        'execute_member' => '參與對象',
        'execute_content' => '執行紀錄',
        #
        #file
        'file' => '檔案下載',
        #
        #home_banner
        'home_banner-name' => '名稱',
        'home_banner-photo' => '圖片',
        'home_banner-url' => '網址',
        #
        #home_course_month
        'home_course_month-name' => '名稱',
        'home_course_month-url' => '超連結',
        #
        #home_course_year
        'home_course_year-name' => '名稱',
        'home_course_year-file' => '檔案',
        #
        #id_type
        'id_type' => '身份別',
        #
        #info_event
        'info_event-event_date' => '活動日期',
        'info_event-info' => '活動資訊',
        'info_event-location' => '活動地點',
        'info_event-url' => '網址',
        #
        #info_news
        'info_news-publish_date' => '發佈日期',
        'info_news-name' => '新聞標題',
        'info_news-content' => '檔案',
        'info_news-view' => '瀏覽人數',
        'info_news-show_date_start' => '上架日期',
        'info_news-show_date_end' => '下架日期',
        #
        #info_promotion
        'info_promotion-publish_date' => '發佈日期',
        'info_promotion-name' => '標題',
        'info_promotion-content' => '內容',
        'info_promotion-view' => '瀏覽人數',
        'info_promotion-show_date_start' => '上架日期',
        'info_promotion-show_date_end' => '下架日期',
        #
        #image
        'image-main' => '主圖',
        #
        #instrument
        'instrument' => '儀器',
        'instrument_name' => '儀器名稱',
        'instrument_count' => '儀器數量',
        'instrument_type' => '儀器所屬平台',
        'instrument_id' => '儀器編號',
        'instrument_function' => '設施功能簡述',
        #
        #item
        'item' => '項目',
        'item-add' => '已加入項目',
        'item-main' => '主項目',
        'item-main-add' => '已加入主項目',
        'item-sub' => '子項目',
        'item-sub-add' => '已加入子項目',
        #
        #is
        'is_reply' => '是否回覆',
        'is_notice' => '使否為公告',
        'is_pass' => '是否通過',
        'item_count' => '數量',
        #
        #level
        'level' => '等級',
        #
        #location
        'location' => '地區',
        #
        #learning
        'learning_time' => '時間',
        'learning_member' => '參與對象',
        'learning_content' => '研習記錄',
        'learning_file' => '相關手冊',
        'learning_photo' => '研習照片',
        #
        #message
        'message' => '留言',
        #
        #member
        'member-email' => 'E-mail(登入帳號)',
        'member-password' => '密碼',
        'member-password_confirmation' => '確認密碼',
        'member-name_cht' => '中文姓名',
        'member-name_eng' => '英文姓名',
        'member-name_eng1' => 'First Name',
        'member-name_eng2' => 'Last Name',
        'member-idnumber' => '身分證字號後五碼',
        'member-ispassport' => '國外人士請勾選並填寫護照號碼',
        'member-sex' => '性別',
        'member-birthday' => '出生日期',
        'member-title' => '職稱',
        'member-work_name' => '公司名稱/學校名稱',
        'member-town_id' => '聯絡地址區域',
        'member-address' => '聯絡地址',
        'member-phone' => '電話',
        'member-phone_ext' => '分機',
        'member-cellphone' => '行動電話',
        'member-order_epaper' => '是否訂閱電子報',
        'member-name' => '學員姓名',
        'member-email2' => 'E-mail',
        #
        #max
        'max_stock' => '最大庫存量',
        #
        #name
        'name' => '名稱',
        'name-main_type' => '主類型名稱',
        'name-sub_type' => '副類型名稱',
        'name-school' => '校名',
        'name-stage' => '階段名稱',
        'name-target' => '指標名稱',
        'name-contact' => '承辦人員姓名',
        #
        #open
        'open_instrument' => '通過後可開通的設備',
        'open_instrument_add' => '已加入的設備',
        'open_section' => '可使用時段',
        #
        #order
        'order' => '排序',
        #
        #page
        'page_id' => '單號',
        #
        #pass
        'pass_type' => '通過方式',
        'pass_condition' => '通過條件',
        #
        #pay_status
        'pay_status' => '付款狀態',
        'package' => '組',
        'permission' => '權限',
        #
        #pi
        'pi' => '指導教授',
        #
        #plain
        'plan-topic' => '計畫主題',
        'plan-idea' => '計畫理念',
        'plan-class' => '課程規劃',
        'plan-file' => '附件',
        #
        #plateform
        'plateform_name' => '平台名稱',
        #
        #photo
        'photo' => '照片',
        #
        #phone
        'phone' => '聯絡電話',
        'phone-contact' => '承辦人員電話', 
        #
        #rate
        'rate' => '單價',
        'rate1' => '校外產業單價',
        'rate2' => '校外學術單價',
        'rate3' => '台大校內單價',
        'rate4' => '生科院內單價',
        'rate_type' => '計價方式',
        'rate_multi' => '計價倍率',
        #
        #reservation
        'reservation_count' => '預約人數',
        'related_instrument' => '相關儀器',
        'reservation_at' => '預約日期',
        'reservation_limit' => '每人預約次數上限',
        'reservation_notice' => '使用前一日提醒使者',
        'reservation_cancel_limit' => '自行取消截止日',
        'reservation_cancel_notice' => '自行取消後通知管理員',
        'reservation_section' => '預約時段',
        'reservation_status' => '預約狀態',
        #
        #reply
        'reply-content' => '回覆內容',
        'reply-dt' => '回覆日期',
        'reply-admin' => '回覆管理員',
        'remark' => '備註',
        'recommend' => '回饋建議',
        'recommend2' => '官方評論',
        'related_group' => '相關合作場域 / 團體',
        'related_web' => '相關網站',
        #
        #related_plateform
        'related_plateform' => '相關平台',
        'related_plateform_add' => '已加入相關平台',
        #
        #sale
        'sale_count' => '銷售數量',
        'school' => '學校',
        'school_others' => '其他學校',
        'score' => '學分數',
        'score_pass' => '成績',
        #
        #section
        'section' => '使用時段',
        'section_type' => '時段類別',
        #
        #site
        'site_name' => '場地名稱',
        #
        #start_dt
        'start_dt' => '開始日期',
        'start_time' => '開始時間',
        #
        #system
        'system-department' => '部門',
        'system-organize' => '單位',
        #
        #tel
        'tel' => '電話',
        'tutor_time' => '訪視時間',
        'tutor_member' => '訪視委員',
        'tutor_content' => '訪視紀錄',
        #
        #template
        'template_id' => '樣板ID',
        'template-file' => '樣板檔案',
        'template-selected' => '已選擇樣板',
        #ts_cert
        'ts_cert-content' => '編輯器內容',
        #
        #ts_customized
        'ts_customized-content' => '編輯器內容',
        #
        #ts_digital
        'ts_digital-content' => '編輯器內容',
        'ts_digital-file' => '有效資源',
        #
        #ts_it
        'ts_it-content' => '編輯器內容',
        #
        #ts_management
        'ts_management-name' => '名稱',
        'ts_management-content' => '編輯器內容',
        #
        #ts_training
        'ts_training-content' => '編輯器內容',
        #
        #ts_user
        'ts_user-content' => '編輯器內容',
        #
        #ts_user_tabs
        'ts_user_tabs-name' => '名稱',
        'ts_user_tabs-content' => '編輯器內容',
        #time
        'time' => '時數',
        #
        #video
        'video_date' => '影片日期',
        'video_url' => '影片連結',
        #
        #city
        'city_name' => '縣市',
        #
        #town
        'town_name' => '區域',
        'town_zip' => '郵遞區號',
        'total' => '總金額',
        'text' => '文字描述', 
        'title' => '標題',
        #update
        'update_at' => '異動日期',
        'user' => '使用者',
        #
        #通用
        'id' => '系統編號',
        'created_at' => '新增日期',
        'created_at-start' => '新增日期開始',
        'created_at-end' => '新增日期結束',
        'create_admin_id' => '新增人員',
        'updated_at' => '更新日期',
        'update_admin_id' => '更新人員',
        'enable' => '狀態',
        'password_old' => '舊密碼',
        'password_new' => '新密碼',
        'password_new_confirmation' => '確認新密碼',
        'no' => '編號',
        'upload_file' => '請上傳檔案',
        'upload_image' => '請上傳圖片',
        'keyword' => '關鍵字',
        'keyword_2' => '關鍵字(Keywords)',
    ],
];
