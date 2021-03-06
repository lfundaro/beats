<? require_once("header.php");
   drawHeader(); ?>
  <div class="content-box">
    <h2>Getting Started</h2>
    <p>Let&#8217;s make some beats! First thing, you gotta <a href="/download">download and install Beats</a>.</p>
    <p>Next, you&#8217;ll need some <code>*.wav</code> sound files. Download this set of <a href="/casio_sa20_drum_sounds.zip">sounds sampled from an ancient Casio keyboard</a>. Unzip that file to a folder.</p>
  </div>
  <div class="content-box">
    <h2>Hello World Beat</h2>
    <p>Create a new file in the same folder as the drum sounds you just downloaded. Call it something like <code>song.txt</code>. Open it in a text editor and type the following. (Make sure you indent the lines properly).</p>
    <p><pre><code>Song:
  Flow:
    - Verse

Verse:
  - bass.wav:  X...X...X...X...</code></pre></p>
    <p>This is about the most minimal song you can create. To turn it into a Wave file, run the following from the command line:</p>
    <p><pre><code>beats song.txt song.wav</code></pre></p>
    <p>If Beats ran successfully, it should print something like the following:</p>
    <p><pre><code>0:02 of audio written in 0.052459 seconds.</code></pre></p>
    <p>There should now be a file called <code>song.wav</code> in the current directory. Play it in whatever media player you like. Pretty sweet!</p>
  </div>
  <div class="content-box">
  <h2>Changing the Tempo</h2>
  <p>By default, your song will play at 120 beats per minute. You can also specify your own tempo in the song header. This will play faster:</p>
  <p><pre><code>Song:
  Tempo: 200
  Flow:
    - Verse

Verse:
  - bass.wav:  X...X...X...X...</code></pre></p>
  <p>And this will play more slowly:</p>
  <p><pre><code>Song:
  Tempo: 60
  Flow:
    - Verse

Verse:
  - bass.wav:  X...X...X...X...</code></pre></p>
  </div>
  <div class="content-box">
  <h2>Adding More Tracks</h2>
  <p>That four-on-the-floor bass rhythm is nice, so let&#8217;s build on top of it. To add a snare drum and a hi-hat, just add two more lines to the <code>Verse</code> pattern:</p>
  <p><pre><code>Song:
  Tempo: 120
  Flow:
    - Verse

Verse:
  - bass.wav:       X...X...X...X...
  - snare.wav:      ....X.......X...
  - hh_closed.wav:  X.X.X.X.XX.XXXXX

</code></pre></p>
  </div>
  <div class="content-box">
  <h2>Repeating Patterns</h2>
  <p>You can repeat patterns in the flow. Add <code>x4</code> to repeat the <code>Verse</code> pattern four times.</p>
  <p><pre><code>Song:
  Tempo: 120
  Flow:
    - Verse:  x4

Verse:
  - bass.wav:       X...X...X...X...
  - snare.wav:      ....X.......X...
  - hh_closed.wav:  X.X.X.X.XX.XXXXX

</code></pre></p>
  </div>
  <div class="content-box">
  <h2>Adding a New Pattern</h2>
  <p>Now let&#8217;s add another pattern. Call it <code>Chorus</code>. Don&#8217;t forget to add it to the flow. Notice the optional bar line used to separate the two measures in the new pattern.</p>
  <p><pre><code>Song:
  Tempo: 120
  Flow:
    - Verse:  x4
    - Chorus: x4

Verse:
  - bass.wav:         X...X...X...X...
  - snare.wav:        ....X.......X...
  - hh_closed.wav:    X.X.X.X.XX.XXXXX

Chorus:
  - bass.wav:         XXXXXXXXXXXXX...|XXXXXXXXXXXXX...
  - snare.wav:        ....X.......X...|....X.......X...
  - hh_closed.wav:    XXXXXXXXXXXXX...|XXXXXXXXXXXXX...
  - conga_low.wav:    X.....X.X..X....|X.X....XX.X.....
  - conga_high.wav:   ....X....X......|................
  - cowbell_high.wav: ................|..............X.</code></pre></p>
  </div>
<? drawFooter(); ?>
