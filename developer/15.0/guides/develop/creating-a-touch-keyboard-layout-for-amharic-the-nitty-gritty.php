<?php
  require_once('includes/template.php');

  head([
    'title' => "Creating a Touch Keyboard Layout Part 2"
  ]);
?>

<style>
  /* Custom OSK font for key type */
  @font-face {
    font-family: SpecialOSK;
    font-display: block;
    src: url('https://s.keyman.com/kmw/engine/15.0.222/osk/keymanweb-osk.ttf');
  }

  .special-osk {
    text-align: center;
    font-family: SpecialOSK;
  }
</style>

<h1 class="title" id="touch_layout_2">Creating a Touch Keyboard Layout Part 2</h1>

  <p>This article will continue the guide to creating a touch keyboard layout. In particular, we'll look in more detail at how keys
  are arranged, just what can be specified for each key, and lastly, how this all looks in the JSON code used to define the long press
  layout.</p>

  <h2><a name="id488340" id="id488340"></a>Key and Key Layer Organization</h2>

  <p>There are two issues that are immediately apparent when considering key layout on touch devices.</p>

  <p>First, on smaller touch devices, such as phones, if we try to display the same arrangement of keys that is used for a typical
  desktop keyboard, the keys are so small that it is difficult to reliably select the wanted key. If used in 'portrait' view, key
  widths are too narrow for our 'fat fingers':</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_4.png')?>"></p>

  <p>Or if in 'landscape' view, key heights are too small:</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_5.png')?>"></p>

  <p>The situation is improved markedly if we limit the number of keys per row to ten and have no more than four key
  rows:</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_6.png')?>"></p>

  <p>and, in landscape view:</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_7.png')?>"></p>

  <p>So which keys can be eliminated, and which keys must be on the base (default) layer? This brings us to the second point. When using
  phones and other touch layout devices rather than desktop keyboards, text entry is most often single-handed, which makes it best to
  avoid using the 'shift' layer for entering normal text. Secondary keyboard layers will, of course, still be usually needed for
  uppercase, numerals and symbols, but will be used much less frequently.</p>

  <p>Most desktop keyboards (for European languages, at least) are already laid out with no more than ten letter (or digit) keys per row,
  with the remaining keys being used for accented letters, punctuation and other non-letter input. So the obvious choice is to move
  non-letter keys (and accented letters) either to a secondary key layer, or to long press ('pop-up') keys. The GFF Amharic
  desktop keyboard is fortunately also arranged with only ten letters per key row (other keys being used for punctuation, etc.), as the
  many different characters in the Amharic 'abugida' are generated by multi-letter sequences rather than being displayed on
  separate keys. The Geez script does not have both upper-case and lower-case forms, so those initial consonants that require use of the
  'shift' layer on the desktop keyboard have been added to the corresponding base-layer key as long press keys:</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_8.png')?>"></p>

  <p>Arrangement of punctuation and other non-letter keys is more flexible as mobile users are generally familiar with using long press
  keys or a secondary key layer for finding and entering digits and special characters. However, some punctuation characters are used so
  frequently that they need to be on the base layer. For the GFF Amharic keyboard, the most frequently used punctuation characters can be
  output from the base layer using standard or long press keys. The Geez word space character, in particular, is so frequently used that
  it was considered useful to add it to the bottom key, adjacent to the space bar, as is sometimes done for other scripts, such as
  Japanese, on desktop (physical) keyboards.</p>

  <h2><a name="id488436" id="id488436"></a>Arranging keys with the layout editor</h2>

  <p>The Keyman Developer layout editor really makes it quite easy to try different key layouts and choose what is best for your
  keyboard. The image below highlights just how, for any selected key, using the clickable icons circled, a key row can be added above
  (1) or below (2), a key added before (3) or after (4), the selected key deleted (5), and how an array of long press keys (sometimes
  called "subkeys") can be added (6).</p>

  <p><img src="<?= cdn('img/developer/100/touch_amharic_keyboard_9.png')?>"></p>

  <h2>Key properties</h2>

  <p>For each visual key, the appearance and behaviour is determined by a number of properties:</p>

  <h3>Key code</h3>

  <p>Each key must be given an identifying key code which is unique to the key layer. Key codes by and large correspond to the virtual
  key codes used when creating a keyboard program for a desktop keyboard, and should start with <code class="language-keyman">K_</code>, for keys mapped to standard Keyman
  virtual key names, e.g. <code class="language-keyman">K_HYPHEN</code>, and <code class="language-keyman">T_</code> or <code class="language-keyman">U_</code> for user-defined names, e.g. <code class="language-keyman">T_ZZZ</code>. If keyboard rules exist matching the key code in
  context, then the output from the key will be determined by the processing of those rules. It is usually best to include explicit rules
  to manage the output from each key, but if no rules matching the key code are included in the keyboard program, and the key code
  matches the pattern <code class="language-keyman">U_<var>xxxx</var>[_<var>yyyy</var>...]</code> (where <code class="language-keyman"><var>xxxx</var></code> and
  <code class="language-keyman"><var>yyyy</var></code> are 4 to 6-digit hex strings), then the Unicode characters <code class="language-keyman">U+<var>xxxx</var></code> and
  <code class="language-keyman">U+<var>yyyy</var></code> will be output. As of Keyman 15, you can use more than one Unicode character value in the id (earlier versions permitted
  only one). T4he key code is always required, and a default code will usually be generated automatically by Keyman Developer.</p>

  <div class="itemizedlist">
    <ul type="opencircle">
      <li class="c1">
        <p><code class="language-keyman"><var>K_xxxx</var></code> is used for a standard Keyman Desktop key name, e.g. <code class="language-keyman">K_W</code>, <code class="language-keyman">K_ENTER</code>. You cannot make up your own <code class="language-keyman">K_<var>xxxx</var></code> names. Many of
        the <code class="language-keyman">K_</code> ids have overloaded output behaviour, for instance, if no rule is matched for <code class="language-keyman">K_W</code>, Keyman will output 'w' when it
        is touched. The standard key names are listed in <a class="link" href="/developer/language/guide/virtual-keys" title=
        "Virtual Keys and Virtual Character Keys">Virtual Keys and Virtual Character Keys</a>. Typically, you would use only the
        "common" virtual key codes.</p>
      </li>

      <li class="c1">
        <p><code class="language-keyman">T_<var>xxxx</var></code> is used for any user defined names, e.g. <code class="language-keyman">T_SCHWA</code>. If you wanted to use it, <code class="language-keyman">T_ENTER</code> would also be valid. If no rule
        matches it, the key will have no output behaviour.</p>
      </li>

      <li class="c1">
        <p><code class="language-keyman">U_<var>####</var>[_<var>####</var>]</code> is used as a shortcut for a key that will output those Unicode values, if no rule matches it. This is similar to
        the overloaded behaviour for <code class="language-keyman">K_</code> ids. Thus <code class="language-keyman"><var>####</var></code> must be valid Unicode characters. E.g. <code class="language-keyman">U_0259</code> would generate a schwa if no rule
        matches. It is still valid to have a rule such as <code class="language-keyman">+ [U_0259] > ...</code></p>
      </li>
    </ul>
  </div>

  <p>As noted above, some <code>K_xxxx</code> codes emit characters, if no rule is defined. There are also some codes which have  special functions:</p>

  <div class="table">
    <p class="title"><b>Table 2.1. </b></p>

    <div class="table-contents">
      <table class="display">
        <thead>
          <tr>
            <th>Identifier</th>
            <th>Meaning</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td ><code class="language-keyman">K_ENTER</code></td>
            <td>Submit a form, or add a new line (multi-line); the key action may vary depending on the situation.</td>
          </tr>
          <tr>
            <td ><code class="language-keyman">K_BKSP</code></td>
            <td>Delete back a single character. This key, if held down, will repeat. It is the only key code which triggers
              repeat behavior.</td>
            </tr>
            <tr>
              <td ><code class="language-keyman">K_LOPT</code></td>
              <td>Open the language menu (aka Globe key).</td>
            </tr>
            <tr>
              <td ><code class="language-keyman">K_ROPT</code></td>
              <td>Hide the on screen keyboard.</td>
            </tr>
            <tr>
              <td ><code class="language-keyman">K_TAB</code><br><code>K_TABBACK</code><br><code>K_TABFWD</code></td>
              <td>Move to next or previous element in a form. Note that these key functions are normally implemented
                  outside the touch layout, so should not typically be used. <code>K_TAB</code> will go to previous
                  element if used with the <code>shift</code> modifier.</td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>

  <p>Any key can be used to switch keyboard layers (see <a href="#toc-nextlayer"><code>nextlayer</code></a>), but the following layer-switching key
  codes have been added for switching to some commonly used secondary layers. Note that these keys
  have no specific meaning; you must still set the <code>nextlayer</code> property on the key.</p>

  <div class="table">
    <p class="title"><b>Table 2.1. </b></p>

    <div class="table-contents">
      <table class="display">
        <colgroup>
          <col>
          <col>
        </colgroup>

        <thead>
          <tr>
            <th >Identifier</th>
            <th >Meaning</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td ><code class="language-keyman">K_NUMERALS</code></td>
            <td>Switch to a numeric layer</td>
          </tr>

          <tr>
            <td><code class="language-keyman">K_SYMBOLS</code></td>
            <td>Switch to a symbol layer</td>
          </tr>

          <tr>
            <td><code class="language-keyman">K_CURRENCIES</code></td>
            <td>Switch to a currency layer</td>
          </tr>

          <tr>
            <td><code class="language-keyman">K_SHIFTED</code></td>
            <td>Switch to a shift layer</td>
          </tr>

          <tr>
            <td><code class="language-keyman">K_ALTGR</code></td>
            <td>Switch to a right-alt layer (desktop compatibility)</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div><br class="table-break">

  <h3><a name="key-text" id="key-text"></a>Key text</h3>

  <p>The key text is simply the character (or characters) that you want to appear on the key cap. This will usually be the same as the
  characters generated when the key is touched, unless contextual rules are used to generate output according to a multi-key sequence, as
  will be true for the GFF Amharic keyboard. Unicode characters can be specified either as a string using a target font or using the
  standard hex notation <code class="language-keyman">\uxxxx</code>. This may be sometimes more convenient, for example, for characters from an uninstalled font, or for
  diacritic characters that do not render well alone.</p>

  <p>A number of special text labels are recognized as identifying special purpose keys, such as Shift, Backspace, Enter, etc., for which
  icons are more appropriately used than a text label. A special font including these icons is included with Keyman and automatically
  embedded and used in any web page using Keyman. The list of icons in the font will probably be extended in future, but for now the
  following special labels are recognized:</p>

  <div class="table">
    <p class="title"><b>Table 2.2. </b></p>

    <div class="table-contents">
      <table class="display">
        <colgroup>
          <col>
          <col>
          <col>
        </colgroup>

        <thead>
          <tr>
            <th>Text String</th>
            <th>Key Cap</th>
            <th>Key Purpose</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td><code>*Shift*</code></td>
            <td class='special-osk'>&#xE008;</td>
            <td>Select Shift layer (inactive). Use on the Shift key to indicate that it switches to the shift layer.</td>
          </tr>
          <tr>
            <td><code>*Shifted*</code></td>
            <td class='special-osk'>&#xE009;</td>
            <td>Select Shift layer (active). Use on the Shift key on the shift layer to switch back to the default layer.</td>
          </tr>
          <tr>
            <td><code>*ShiftLock*</code></td>
            <td class='special-osk'>&#xE073;</td>
            <td>Switch to Caps layer (inactive). Not commonly used; generally double-tap on Shift key is used to access the caps layer.</td>
          </tr>
          <tr>
            <td><code>*ShiftedLock*</code></td>
            <td class='special-osk'>&#xE074;</td>
            <td>Switch to Caps layer (active). Use on the Shift key on the caps layer to switch back to the default layer.</td>
          </tr>
          <tr>
            <td><code>*Enter*</code></td>
            <td class='special-osk'>&#xE005; or &#xE071;</td>
            <td>Return or Enter key (shape determined by writing system direction)</td>
          </tr>
          <tr>
            <td><code>*LTREnter*</code></td>
            <td class='special-osk'>&#xE005;</td>
            <td>Return or Enter key (left-to-right script shape)</td>
          </tr>
          <tr>
            <td><code>*RTLEnter*</code></td>
            <td class='special-osk'>&#xE071;</td>
            <td>Return or Enter key (right-to-left script shape)</td>
          </tr>
          <tr>
            <td><code>*BkSp*</code></td>
            <td class='special-osk'>&#xE004; or &#xE072;</td>
            <td>Backspace key (shape determined by writing system direction)</td>
          </tr>
          <tr>
            <td><code>*LTRBkSp*</code></td>
            <td class='special-osk'>&#xE004;</td>
            <td>Backspace key (left-to-right script shape)</td>
          </tr>
          <tr>
            <td><code>*RTLBkSp*</code></td>
            <td class='special-osk'>&#xE072;</td>
            <td>Backspace key (right-to-left script shape)</td>
          </tr>
          <tr>
            <td><code>*Menu*</code></td>
            <td class='special-osk'>&#xE00B;</td>
            <td>Globe key; display the language menu. Use on the <code>K_LOPT</code> key.</td>
          </tr>
          <tr>
            <td><code>*Hide*</code></td>
            <td class='special-osk'>&#xE00A;</td>
            <td>Hide the on screen keyboard. Use on the <code>K_ROPT</code> key.</td>
          </tr>
          <tr>
            <td><code>*ABC*</code></td>
            <td class='special-osk'>&#xE010;</td>
            <td>Select alphabetic layer (Uppercase)</td>
          </tr>
          <tr>
            <td><code>*abc*</code></td>
            <td class='special-osk'>&#xE011;</td>
            <td>Select alphabetic layer (Lowercase)</td>
          </tr>
          <tr>
            <td><code>*123*</code></td>
            <td class='special-osk'>&#xE013;</td>
            <td>Select the numeric layer</td>
          </tr>
          <tr>
            <td><code>*Symbol*</code></td>
            <td class='special-osk'>&#xE015;</td>
            <td>Select the symbol layer</td>
          </tr>
          <tr>
            <td><code>*Currency*</code></td>
            <td class='special-osk'>&#xE014;</td>
            <td>Select the currency symbol layer</td>
          </tr>
          <tr>
            <td><code>*ZWNJ*</code></td>
            <td class='special-osk'>&#xE075; (iOS) or &#xE076; (Android)</td>
            <td>Zero Width Non Joiner (shape determined by current platform)</td>
          </tr>
          <tr>
            <td><code>*ZWNJiOS*</code></td>
            <td class='special-osk'>&#xE075;</td>
            <td>Zero Width Non Joiner (iOS style shape)</td>
          </tr>
          <tr>
            <td><code>*ZWNJAndroid*</code></td>
            <td class='special-osk'>&#xE076;</td>
            <td>Zero Width Non Joiner (Android style shape)</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div><br class="table-break">

  <p>The following additional symbols are also available, but intended for
  working with legacy desktop layouts, and not recommended for general use:</p>

  <div class="table">
    <p class="title"><b>Table 2.2.1. </b></p>

    <div class="table-contents">
      <table class="display">
        <colgroup>
          <col>
          <col>
          <col>
        </colgroup>

        <thead>
          <tr>
            <th>Text String</th>
            <th>Key Cap</th>
            <th>Key Purpose</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td><code>*Tab*</code></td>
            <td class='special-osk'>&#xE006;</td>
            <td>Move to next input element in tab order</td>
          </tr>
          <tr>
            <td><code>*TabLeft*</code></td>
            <td class='special-osk'>&#xE007;</td>
            <td>Move to previous input element in tab order</td>
          </tr>
          <tr>
            <td><code>*Caps*</code></td>
            <td class='special-osk'>&#xE003;</td>
            <td>Select caps layer (legacy)</td>
          </tr>
          <tr>
            <td><code>*AltGr*</code></td>
            <td class='special-osk'>&#xE002;</td>
            <td>Select AltGr (Right-Alt) layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*Alt*</code></td>
            <td class='special-osk'>&#xE019;</td>
            <td>Select Alt layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*Ctrl*</code></td>
            <td class='special-osk'>&#xE001;</td>
            <td>Select Ctrl layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LAlt*</code></td>
            <td class='special-osk'>&#xE056;</td>
            <td>Select Left-Alt layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RAlt*</code></td>
            <td class='special-osk'>&#xE057;</td>
            <td>Select Right-Alt layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LCtrl*</code></td>
            <td class='special-osk'>&#xE058;</td>
            <td>Select Left-Ctrl layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RCtrl*</code></td>
            <td class='special-osk'>&#xE059;</td>
            <td>Select Right-Ctrl layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LAltCtrl*</code></td>
            <td class='special-osk'>&#xE060;</td>
            <td>Select Left-Alt-Ctrl layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RAltCtrl*</code></td>
            <td class='special-osk'>&#xE061;</td>
            <td>Select Right-Alt-Ctrl layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LAltCtrlShift*</code></td>
            <td class='special-osk'>&#xE062;</td>
            <td>Select Left-Alt-Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RAltCtrlShift*</code></td>
            <td class='special-osk'>&#xE063;</td>
            <td>Select Right-Alt-Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*AltShift*</code></td>
            <td class='special-osk'>&#xE064;</td>
            <td>Select Alt-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*CtrlShift*</code></td>
            <td class='special-osk'>&#xE065;</td>
            <td>Select Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*AltCtrlShift*</code></td>
            <td class='special-osk'>&#xE066;</td>
            <td>Select Alt-Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LAltShift*</code></td>
            <td class='special-osk'>&#xE067;</td>
            <td>Select Left-Alt-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RAltShift*</code></td>
            <td class='special-osk'>&#xE068;</td>
            <td>Select Right-Alt-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*LCtrlShift*</code></td>
            <td class='special-osk'>&#xE069;</td>
            <td>Select Left-Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          <tr>
            <td><code>*RCtrlShift*</code></td>
            <td class='special-osk'>&#xE070;</td>
            <td>Select Right-Ctrl-Shift layer (desktop layout compatibility)</td>
          </tr>
          </tbody>
      </table>

    </div>
  </div><br class="table-break">

  <h3><a name="key-type" id="key-type"></a>Key type</h3>

  <p>The general appearance of each key is determined by the key type, which is selected (in Keyman Developer) from a drop-down
  list. While generally behavior is not impacted by the key type, Spacer keys cannot be selected.</p>

  <div class="table">
    <a name="id488808" id="id488808"></a>

    <p class="title"><b>Table 2.3. </b></p>

    <div class="table-contents">
      <table class="display">
        <colgroup>
          <col>
          <col>
          <col>
        </colgroup>

        <thead>
          <tr>
            <th>Key Type</th>
            <th>Value</th>
            <th>Meaning</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Default</td>
            <td><code class="language-keyman">0</code></td>
            <td>Any normal key that emits a character</td>
          </tr>

          <tr>
            <td>Special</td>
            <td><code class="language-keyman">1</code></td>
            <td>The frame keys such as Shift, Enter, BkSp.</td>
          </tr>

          <tr>
            <td>Special (active)</td>
            <td><code class="language-keyman">2</code></td>
            <td>A frame key which is currently active, such as the Shift key on the shift layer.</td>
          </tr>

          <tr>
            <td>Deadkey</td>
            <td><code class="language-keyman">8</code></td>
            <td>Does not impact behavior, but colors the key differently to indicate it has a special function,
                such as a desktop-style deadkey.</td>
          </tr>

          <tr>
            <td>Blank</td>
            <td><code class="language-keyman">9</code></td>
            <td>A blank key, which may be used to maintain a layout shape. Usually colored differently. Does not
              impact behavior.</td>
          </tr>

          <tr>
            <td>Spacer</td>
            <td><code class="language-keyman">10</code></td>
            <td>Does not render the key, but leaves a same-sized gap in its place. The key cannot be selected.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div><br class="table-break">

  <p>The colour, shading and borders of each key type is actually set by a style sheet which can be customized by the page developer.</p>

  <h3><a name="font-family" id="font-family"></a>font-family</h3>

  <p>If a different font is required for a particular key text, the <code class="language-keyman">font-family</code> name can be specified. The font used to display icons for
  the special keys (as mentioned above) does not need to be specified, as it will be automatically applied to a key that uses any of the
  special key text labels.</p>

  <h3><a name="font-size" id="font-size"></a>font-size</h3>

  <p>If a particular key cap text requires a different font size from the default for the layout, it should be specified in em units.
  This can be helpful if a the key text is either an unusually large character or, alternatively, a word or string of several characters
  that would not normally fit on the key.</p>

  <h3><a name="width" id="width"></a>width</h3>

  <p>The layout is scaled to fit the widest row of keys in the device width, assuming a default key width of 100 units. Keys that are to
  be wider or narrower than the default width should have width specified as a percentage of the default width. For any key row that is
  narrower than the widest row, the width of the last key in the row will be automatically increased to align the right hand side of the
  key with the key with the right edge of the keyboard. However, where this is not wanted, a "spacer" key can be inserted to
  leave a visible space instead. As shown in the above layouts, where the spacer key appears on the designer screen as a narrow key, but
  will not be visible in actual use.</p>

  <h3><a name="pad" id="pad"></a>pad</h3>

  <p>Padding to the left of each key can be adjusted, and specified as a percentage of the default key width. If not specified, a
  standard padding of 5% of the key width is used between adjacent keys.</p>

  <h3><a name="layer" id="layer"></a>layer</h3>

  <p>To simplify correspondence with desktop keyboards and avoid the need for using a separate keyboard mapping program, touch layout
  keys can specify a desktop keyboard layer that the keystroke should be interpreted as coming from. Layer names of <code class="language-keyman">shift</code>, <code class="language-keyman">ctrl</code>, <code class="language-keyman">alt</code>,
  <code class="language-keyman">ctrlshift</code>, <code class="language-keyman">altshift</code>, <code class="language-keyman">ctrlalt</code> and <code class="language-keyman">ctrlaltshift</code> can be used to simulate use of the appropriate modifier keys when processing rules.</p>

  <h3><a name="nextlayer" id="nextlayer"></a>nextlayer</h3>

  <p>The virtual keys <code class="language-keyman">K_SHIFT</code>, <code class="language-keyman">K_CONTROL</code>, <code class="language-keyman">K_MENU</code>, etc. are normally used to switch to another key layer, which is implied by the key
  code. The left and right variants of those key codes, and also additional layer-switching keys mentioned above (<code class="language-keyman">K_NUMERALS</code>, <code class="language-keyman">K_SYMBOLS</code>,
  <code class="language-keyman">K_CURRENCIES</code>, <code class="language-keyman">K_ALTGR</code>) can also be used to automatically switch to the appropriate key layer instead of outputting a character.
  However, it is sometimes useful for a key to output a character first, then switch to a new layer, for example, switching back to the
  default keyboard layer after a punctuation key on a secondary layer had been used. Specifying the <code class="language-keyman">nextlayer</code> for a key allows a
  different key layer to be selected automatically following the output of the key. Of course, that can be manually overridden by
  switching to a different layer if preferred.</p>

  <p>Another way the <code class="language-keyman">nextlayer</code> property can be used is for a non-standard layer switching key. So, for example, for the GFF Amharic
  keyboard phone layout, switching back to the base layer uses a <code class="language-keyman">T_ALPHA</code> key code, in which <code class="language-keyman">nextlayer</code> is set as default. In this case, it
  is also necessary to add a rule to the keyboard program:</p>

  <pre class='language-keyman'><code class="language-keyman">+ [T_ALPHA] > nul</code></pre>

  <p>to ensure that the key's scan code is ignored by the keyboard mapping.</p>

  <p>When a key in a touch layout definition includes a <strong>Next Layer</strong> control, this takes precedence over
  setting layer via the <a href='/developer/language/reference/layer'><code>layer</code></a> store (as the <strong>Next Layer</strong>
  control is applied once the rule has finished processing).</p>

  <h3><a name="id488949" id="id488949"></a>subkey</h3>

  <p>Arrays of 'subkeys' or pop-up keys can be defined for any key, and will appear momentarily after the key is touched if not
  immediately released. This provides a major advantage over physical desktop keyboards in that many more keys can be made available from
  a single layer, without cluttering up the basic appearance of the layout. For the GFF Amharic keyboard, we have already noted how such
  subkey arrays are used to manage the extra keys that, on the desktop keyboard, would appear in the shift layer. But they are also used
  to provide another way to enter the two different types of each syllable-initial vowels (glottal or pharyngeal), as a visual
  alternative to pressing the key twice.</p>

  <p>The same properties that are defined for standard keys can also be specified for each subkey except that the width of each key in a
  subkey array will always be the same as the width of the key that causes the subkeys to be shown, and key spacing always uses the
  default padding value.</p>

  <p>The GFF Amharic keyboard, like many others, is mnemonic, so it is useful to also display the standard key cap letter that would
  appear on the key of a desktop keyboard. This is enabled globally in the On-Screen layout editor and applies to both the On-Screen
  keyboard and touch layouts.</p>

  <h2><a name="id488961" id="id488961"></a>Representing (and editing) the visual layout with JSON code</h2>

  <p>In case you are wondering, 'Why do I need to know that?', the reason is that, just as with keyboard mapping code, it is
  sometimes easier to edit a text specification than to use the GUI layout design tool. Keyman Developer switches seamlessly between
  the visual layout tool and the code editor, unless, of course, careless editing of the code results in invalid JSON syntax!</p>

  <p>The GFF Amharic phone layout code starts as:</p>
  <pre class="language-javascript"><code class="language-keyman">
  {
    "phone": {
      "font": "Tahoma",
      "layer": [{
        "id": "default",
        "row": [{
          "id": 1,
          "key": [{
              "id": "K_Q",
              "text": "ቅ",
              "pad": "0"
            }, {
              "id": "K_W",
              "text": "ው"
            },

  . . .
</code></pre>

<p>As long as standard JSON syntax is remembered - nested braces {…}, quoted strings "…" for both element names and values,
element or object arrays in square brackets […], and no trailing comma after the last element in an array - it is quite easy to
understand a layout, which will usually comprise a list of two separate JSON objects for tablet and phone.</p>

<p>Now you're ready to create a great touch layout for your own Keyman keyboard!</p>

<p>Other articles on developing touch layouts:</p>

<ul>
  <li><a href="creating-a-touch-keyboard-layout-for-amharic">Creating a touch keyboard layout for Amharic - part 1</a></li>
  <li><a href="../test/keyboard-touch-and-desktop">How to test your keyboard layout — touch and desktop</a></li>
  <li><a href='../test/keyboard-touch-mobile-emulator'>How to test your touch layout in the Google Chrome mobile emulator</a></li>
</ul>

<p>You can distribute your keyboard to other users by following the instructions in this article:</p>

<ul>
  <li><a href="../distribute/packages">Distribute keyboards to Keyman applications</a></li>
</ul>
