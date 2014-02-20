zip: simple-icons-widget.zip

simple-icons-widget.zip: simple-icons-widget.php assets
	zip -r simple-icons-widget.zip simple-icons-widget.php assets -x *4096*.png -x *2048*.png

clean:
	rm -f simple-icons-widget.zip
