module Beats
  class BeatsRunner
    # Each pattern in the song will be split up into sub patterns that have at most this many steps.
    # In general, audio for several shorter patterns can be generated more quickly than for one long
    # pattern, and can also be cached more effectively.
    OPTIMIZED_PATTERN_LENGTH = 4

    def initialize(input_file_name, output_file_name, options)
      @input_file_name =  input_file_name

      if output_file_name.nil?
        output_file_name = File.basename(input_file_name, File.extname(input_file_name)) + ".wav"
      end
      @output_file_name = output_file_name

      @options = options
    end

    def run
      base_path = @options[:base_path] || File.dirname(@input_file_name)
      song, kit = SongParser.new().parse(base_path, File.read(@input_file_name))

      song = normalize_for_pattern_option(song)
      songs_to_generate = normalize_for_split_option(song)

      song_optimizer = SongOptimizer.new()
      durations = songs_to_generate.collect do |output_file_name, song|
        song = song_optimizer.optimize(song, OPTIMIZED_PATTERN_LENGTH)
        AudioEngine.new(song, kit).write_to_file(output_file_name)
      end

      timeElapsed = durations.last

      # Upload this song to a SoundCloud account
      if @options[:share]
        share_song(@output_file_name)
      end

      {:duration => timeElapsed}
    end

  private

    # If the --share option is used, the song is uploaded in a SoundCloud account,
    # this method will ask for account credentials.
    def share_song(output_file_name) 
      require 'soundcloud'
      require 'read-password'
      require 'spinning_cursor'
      puts 'Please provide your SoundCloud credentials: '
      print 'username: '
      username = STDIN.gets
      print 'password: '
      password = Kernel.password()
      begin
        client = SoundCloud.new({
        :client_id     => '38fcbdcfc87e6da26b58ef1be825b64b',
        :client_secret => 'd5d50f0ce5e1b09eac18c73f8eab8cf0',
        :username      =>  username,
        :password      =>  password
        })
      rescue Exception => e
        puts 'Could not authenticate: ' + e.message
      end

      #Check out for client connections
      connections = client.get('/me/connections')
      #Generate list of connections 
      shared_to = []
      connections.each do |connection|
          shared_to.push({:id => connection.id})
      end
      #start uploading
      begin
        SpinningCursor.run do
          banner 'Uploading file'
          type :spinner
          action do
            unless client.nil? 
              client.post('/tracks', :track => {
                :title      => output_file_name,
                :asset_data => File.new(output_file_name),
                :shared_to  => {
                  :connections => shared_to
                }
              })
            end
          end
        end
        rescue Exception => e
          puts e.message
      end
    end

    # If the -p option is used, transform the song into one whose flow consists of
    # playing that single pattern once.
    def normalize_for_pattern_option(song)
      unless @options[:pattern].nil?
        pattern_name = @options[:pattern].downcase.to_sym

        unless song.patterns.has_key?(pattern_name)
          raise StandardError, "The song does not include a pattern called #{pattern_name}"
        end

        song.flow = [pattern_name]
        song.remove_unused_patterns()
      end

      song
    end

    # Returns a hash of file name => song object for each song that should go through the audio engine
    def normalize_for_split_option(song)
      songs_to_generate = {}

      if @options[:split]
        split_songs = song.split()
        split_songs.each do |track_name, split_song|
          # TODO: Move building the output file name into its own method?
          extension = File.extname(@output_file_name)
          file_name = File.dirname(@output_file_name) + "/" +
                      File.basename(@output_file_name, extension) + "-" + File.basename(track_name, extension) +
                      extension

          songs_to_generate[file_name] = split_song
        end
      else
        songs_to_generate[@output_file_name] = song
      end

      songs_to_generate
    end
  end
end
