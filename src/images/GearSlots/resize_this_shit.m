filelist = ls();

for i=1:length(filelist)
    thisfile = strtrim(filelist(i,:));
    if length(thisfile) > 4 && strcmp(thisfile(end-3:end), '.png')
        [im, map, alpha] = imread(filelist(i,:));
        im = im(1:48,1:48,:);
        alpha = alpha(1:48,1:48);
        imwrite(im, thisfile, 'png', 'Alpha', alpha);
    end
end